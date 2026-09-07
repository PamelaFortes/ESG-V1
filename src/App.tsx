import { useState } from 'react'

// ── Types ──────────────────────────────────────────────────────────────────

type Screen =
  | 'login' | 'dashboard' | 'employees' | 'employee-form'
  | 'employee-profile' | 'trainings' | 'training-form'
  | 'register-training' | 'pending'

type TrainingStatus = 'valid' | 'expiring' | 'expired'

interface Employee {
  id: number; name: string; cpf: string; role: string; sector: string
  email: string; phone: string; dob: string; admission: string
  registration: string; status: 'active' | 'inactive'
  trainingsTotal: number; trainingsValid: number
  trainingsExpiring: number; trainingsExpired: number
}

interface Training {
  id: number; name: string; description: string
  hours: number; validityMonths: number
  status: 'active' | 'inactive'; employeeCount: number
}

interface TrainingRecord {
  id: number; employeeId: number; employeeName: string
  employeeRole: string; employeeSector: string
  trainingId: number; trainingName: string
  completedDate: string; expiryDate: string
  status: TrainingStatus; daysLeft: number
}

// ── Mock Data ──────────────────────────────────────────────────────────────

const EMPLOYEES: Employee[] = [
  { id: 1, name: 'Carlos Eduardo Silva', cpf: '123.456.789-01', role: 'Técnico de Manutenção', sector: 'Manutenção Elétrica', email: 'carlos.silva@empresa.com', phone: '(11) 98765-4321', dob: '15/03/1985', admission: '10/02/2020', registration: 'MAT-001', status: 'active', trainingsTotal: 6, trainingsValid: 3, trainingsExpiring: 2, trainingsExpired: 1 },
  { id: 2, name: 'Ana Paula Rodrigues', cpf: '234.567.890-12', role: 'Supervisora de Limpeza', sector: 'Conservação', email: 'ana.rodrigues@empresa.com', phone: '(11) 97654-3210', dob: '22/07/1990', admission: '15/05/2019', registration: 'MAT-002', status: 'active', trainingsTotal: 5, trainingsValid: 5, trainingsExpiring: 0, trainingsExpired: 0 },
  { id: 3, name: 'Roberto Ferreira Santos', cpf: '345.678.901-23', role: 'Eletricista', sector: 'Manutenção Elétrica', email: 'roberto.santos@empresa.com', phone: '(11) 96543-2109', dob: '08/11/1978', admission: '20/08/2018', registration: 'MAT-003', status: 'active', trainingsTotal: 7, trainingsValid: 2, trainingsExpiring: 1, trainingsExpired: 4 },
  { id: 4, name: 'Juliana Oliveira Costa', cpf: '456.789.012-34', role: 'Técnica de Segurança', sector: 'Segurança do Trabalho', email: 'juliana.costa@empresa.com', phone: '(11) 95432-1098', dob: '30/04/1992', admission: '05/01/2021', registration: 'MAT-004', status: 'active', trainingsTotal: 8, trainingsValid: 8, trainingsExpiring: 0, trainingsExpired: 0 },
  { id: 5, name: 'Marcos Antônio Lima', cpf: '567.890.123-45', role: 'Pedreiro', sector: 'Obras Civis', email: 'marcos.lima@empresa.com', phone: '(11) 94321-0987', dob: '14/09/1982', admission: '12/03/2017', registration: 'MAT-005', status: 'active', trainingsTotal: 4, trainingsValid: 1, trainingsExpiring: 1, trainingsExpired: 2 },
  { id: 6, name: 'Fernanda Souza Mendes', cpf: '678.901.234-56', role: 'Auxiliar de Manutenção', sector: 'Manutenção Geral', email: 'fernanda.mendes@empresa.com', phone: '(11) 93210-9876', dob: '01/12/1995', admission: '01/06/2022', registration: 'MAT-006', status: 'inactive', trainingsTotal: 3, trainingsValid: 2, trainingsExpiring: 0, trainingsExpired: 1 },
  { id: 7, name: 'Paulo Henrique Alves', cpf: '789.012.345-67', role: 'Encanador', sector: 'Manutenção Hidráulica', email: 'paulo.alves@empresa.com', phone: '(11) 92109-8765', dob: '25/06/1988', admission: '15/11/2020', registration: 'MAT-007', status: 'active', trainingsTotal: 5, trainingsValid: 2, trainingsExpiring: 2, trainingsExpired: 1 },
  { id: 8, name: 'Sandra Regina Pinto', cpf: '890.123.456-78', role: 'Operadora de Limpeza', sector: 'Conservação', email: 'sandra.pinto@empresa.com', phone: '(11) 91098-7654', dob: '17/01/1983', admission: '04/07/2016', registration: 'MAT-008', status: 'active', trainingsTotal: 4, trainingsValid: 4, trainingsExpiring: 0, trainingsExpired: 0 },
]

const TRAININGS: Training[] = [
  { id: 1, name: 'Integração de Segurança', description: 'Treinamento inicial sobre normas e procedimentos de segurança da empresa', hours: 8, validityMonths: 12, status: 'active', employeeCount: 42 },
  { id: 2, name: 'Trabalho em Altura', description: 'NR-35 — Capacitação para atividades realizadas acima de 2 metros do nível inferior', hours: 16, validityMonths: 12, status: 'active', employeeCount: 18 },
  { id: 3, name: 'Segurança com Ferramentas', description: 'Uso correto e seguro de ferramentas manuais e elétricas no ambiente de trabalho', hours: 4, validityMonths: 24, status: 'active', employeeCount: 35 },
  { id: 4, name: 'Primeiros Socorros', description: 'Procedimentos básicos de primeiros socorros, incluindo RCP e uso do DEA', hours: 8, validityMonths: 24, status: 'active', employeeCount: 12 },
  { id: 5, name: 'Uso de EPI', description: 'Identificação, uso correto e conservação de Equipamentos de Proteção Individual', hours: 4, validityMonths: 12, status: 'active', employeeCount: 48 },
  { id: 6, name: 'Elétrica de Baixa Tensão', description: 'NR-10 — Segurança em instalações e serviços em eletricidade de baixa tensão', hours: 40, validityMonths: 24, status: 'active', employeeCount: 10 },
]

const RECORDS: TrainingRecord[] = [
  { id: 1, employeeId: 3, employeeName: 'Roberto Ferreira Santos', employeeRole: 'Eletricista', employeeSector: 'Manutenção Elétrica', trainingId: 1, trainingName: 'Integração de Segurança', completedDate: '01/08/2025', expiryDate: '01/08/2026', status: 'expired', daysLeft: -29 },
  { id: 2, employeeId: 3, employeeName: 'Roberto Ferreira Santos', employeeRole: 'Eletricista', employeeSector: 'Manutenção Elétrica', trainingId: 2, trainingName: 'Trabalho em Altura', completedDate: '15/07/2025', expiryDate: '15/07/2026', status: 'expired', daysLeft: -46 },
  { id: 3, employeeId: 5, employeeName: 'Marcos Antônio Lima', employeeRole: 'Pedreiro', employeeSector: 'Obras Civis', trainingId: 2, trainingName: 'Trabalho em Altura', completedDate: '20/07/2025', expiryDate: '20/07/2026', status: 'expired', daysLeft: -41 },
  { id: 4, employeeId: 7, employeeName: 'Paulo Henrique Alves', employeeRole: 'Encanador', employeeSector: 'Manutenção Hidráulica', trainingId: 5, trainingName: 'Uso de EPI', completedDate: '10/08/2025', expiryDate: '10/08/2026', status: 'expired', daysLeft: -20 },
  { id: 5, employeeId: 1, employeeName: 'Carlos Eduardo Silva', employeeRole: 'Técnico de Manutenção', employeeSector: 'Manutenção Elétrica', trainingId: 5, trainingName: 'Uso de EPI', completedDate: '05/09/2025', expiryDate: '05/09/2026', status: 'expiring', daysLeft: 6 },
  { id: 6, employeeId: 7, employeeName: 'Paulo Henrique Alves', employeeRole: 'Encanador', employeeSector: 'Manutenção Hidráulica', trainingId: 1, trainingName: 'Integração de Segurança', completedDate: '12/09/2025', expiryDate: '12/09/2026', status: 'expiring', daysLeft: 13 },
  { id: 7, employeeId: 1, employeeName: 'Carlos Eduardo Silva', employeeRole: 'Técnico de Manutenção', employeeSector: 'Manutenção Elétrica', trainingId: 3, trainingName: 'Segurança com Ferramentas', completedDate: '25/09/2024', expiryDate: '25/09/2026', status: 'expiring', daysLeft: 26 },
  { id: 8, employeeId: 5, employeeName: 'Marcos Antônio Lima', employeeRole: 'Pedreiro', employeeSector: 'Obras Civis', trainingId: 5, trainingName: 'Uso de EPI', completedDate: '28/09/2025', expiryDate: '28/09/2026', status: 'expiring', daysLeft: 29 },
  { id: 9, employeeId: 2, employeeName: 'Ana Paula Rodrigues', employeeRole: 'Supervisora de Limpeza', employeeSector: 'Conservação', trainingId: 1, trainingName: 'Integração de Segurança', completedDate: '01/10/2025', expiryDate: '01/10/2026', status: 'valid', daysLeft: 32 },
  { id: 10, employeeId: 4, employeeName: 'Juliana Oliveira Costa', employeeRole: 'Técnica de Segurança', employeeSector: 'Segurança do Trabalho', trainingId: 4, trainingName: 'Primeiros Socorros', completedDate: '15/06/2024', expiryDate: '15/06/2027', status: 'valid', daysLeft: 654 },
  { id: 11, employeeId: 8, employeeName: 'Sandra Regina Pinto', employeeRole: 'Operadora de Limpeza', employeeSector: 'Conservação', trainingId: 5, trainingName: 'Uso de EPI', completedDate: '20/11/2025', expiryDate: '20/11/2026', status: 'valid', daysLeft: 82 },
  { id: 12, employeeId: 1, employeeName: 'Carlos Eduardo Silva', employeeRole: 'Técnico de Manutenção', employeeSector: 'Manutenção Elétrica', trainingId: 6, trainingName: 'Elétrica de Baixa Tensão', completedDate: '10/02/2025', expiryDate: '10/02/2027', status: 'valid', daysLeft: 529 },
  { id: 13, employeeId: 2, employeeName: 'Ana Paula Rodrigues', employeeRole: 'Supervisora de Limpeza', employeeSector: 'Conservação', trainingId: 5, trainingName: 'Uso de EPI', completedDate: '15/01/2026', expiryDate: '15/01/2027', status: 'valid', daysLeft: 138 },
  { id: 14, employeeId: 4, employeeName: 'Juliana Oliveira Costa', employeeRole: 'Técnica de Segurança', employeeSector: 'Segurança do Trabalho', trainingId: 2, trainingName: 'Trabalho em Altura', completedDate: '20/03/2025', expiryDate: '20/03/2026', status: 'expired', daysLeft: -163 },
  { id: 15, employeeId: 3, employeeName: 'Roberto Ferreira Santos', employeeRole: 'Eletricista', employeeSector: 'Manutenção Elétrica', trainingId: 6, trainingName: 'Elétrica de Baixa Tensão', completedDate: '05/04/2024', expiryDate: '05/04/2026', status: 'expired', daysLeft: -147 },
]

// ── Icon Helper ────────────────────────────────────────────────────────────

function Ico({ d, size = 16, className = '' }: { d: string[]; size?: number; className?: string }) {
  return (
    <svg
      width={size} height={size} viewBox="0 0 24 24"
      fill="none" stroke="currentColor"
      strokeWidth={2} strokeLinecap="round" strokeLinejoin="round"
      className={className} aria-hidden="true"
    >
      {d.map((path, i) => <path key={i} d={path} />)}
    </svg>
  )
}

const ic = {
  dashboard: ['M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z', 'M9 22V12h6v10'],
  users: ['M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2', 'M9 7a4 4 0 100 8 4 4 0 000-8z'],
  book: ['M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z', 'M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z'],
  alert: ['M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z', 'M12 9v4', 'M12 17h.01'],
  chart: ['M18 20V10', 'M12 20V4', 'M6 20v-6'],
  search: ['M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0'],
  bell: ['M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9', 'M13.73 21a2 2 0 01-3.46 0'],
  plus: ['M12 5v14', 'M5 12h14'],
  eye: ['M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z', 'M12 9a3 3 0 100 6 3 3 0 000-6z'],
  edit: ['M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7', 'M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z'],
  trash: ['M3 6h18', 'M8 6V4h8v2', 'M19 6l-1 14H6L5 6'],
  shield: ['M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'],
  logout: ['M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4', 'M16 17l5-5-5-5', 'M21 12H9'],
  x: ['M18 6L6 18', 'M6 6l12 12'],
  check: ['M20 6L9 17l-5-5'],
  chevron: ['M9 18l6-6-6-6'],
  upload: ['M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4', 'M17 8l-5-5-5 5', 'M12 3v12'],
  clock: ['M12 22a10 10 0 100-20 10 10 0 000 20z', 'M12 6v6l4 2'],
  arrowLeft: ['M19 12H5', 'M12 19l-7-7 7-7'],
}

// ── Utility Components ─────────────────────────────────────────────────────

function StatusBadge({ status }: { status: TrainingStatus }) {
  const cfg = {
    valid: { label: 'Válido', cls: 'bg-green-50 text-green-700 ring-green-600/20', dot: 'bg-green-500' },
    expiring: { label: 'Próximo do vencimento', cls: 'bg-amber-50 text-amber-700 ring-amber-600/20', dot: 'bg-amber-500' },
    expired: { label: 'Vencido', cls: 'bg-red-50 text-red-700 ring-red-600/20', dot: 'bg-red-500' },
  }[status]
  return (
    <span className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset ${cfg.cls}`}>
      <span className={`size-1.5 rounded-full shrink-0 ${cfg.dot}`} />
      {cfg.label}
    </span>
  )
}

function EmployeeBadge({ status }: { status: 'active' | 'inactive' }) {
  return status === 'active'
    ? <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20"><span className="size-1.5 rounded-full bg-blue-500" />Ativo</span>
    : <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-500 ring-1 ring-inset ring-slate-300/60"><span className="size-1.5 rounded-full bg-slate-400" />Inativo</span>
}

function Avatar({ name, size = 'md' }: { name: string; size?: 'sm' | 'md' | 'lg' }) {
  const initials = name.split(' ').filter(Boolean).map(n => n[0]).slice(0, 2).join('')
  const cls = size === 'sm' ? 'size-7 text-xs' : size === 'lg' ? 'size-14 text-xl' : 'size-8 text-xs'
  return (
    <div className={`${cls} rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold shrink-0`}
      style={{ fontFamily: "'DM Sans', sans-serif" }}>
      {initials}
    </div>
  )
}

// ── Shared table cell styles ───────────────────────────────────────────────
const thCls = 'px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider whitespace-nowrap'
const tdCls = 'px-4 py-3 text-sm text-slate-700'

// ── Sidebar ────────────────────────────────────────────────────────────────

const NAV_ITEMS = [
  { id: 'dashboard', label: 'Dashboard', icon: 'dashboard' },
  { id: 'employees', label: 'Funcionários', icon: 'users' },
  { id: 'trainings', label: 'Treinamentos', icon: 'book' },
  { id: 'pending', label: 'Pendências', icon: 'alert' },
  { id: 'reports', label: 'Relatórios', icon: 'chart', disabled: true },
]

function Sidebar({ screen, navigate }: { screen: Screen; navigate: (s: Screen) => void }) {
  return (
    <aside className="w-60 shrink-0 flex flex-col overflow-hidden" style={{ background: '#1e3a5f' }}>
      <div className="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div className="flex items-center justify-center size-8 rounded-lg bg-blue-500 shrink-0">
          <Ico d={ic.shield} size={15} className="text-white" />
        </div>
        <div>
          <div className="text-white font-bold text-sm leading-tight" style={{ fontFamily: "'DM Sans', sans-serif" }}>SafeTrack</div>
          <div className="text-blue-200/60 text-[10px] leading-tight">Segurança do Trabalho</div>
        </div>
      </div>

      <nav className="flex-1 px-3 py-4 space-y-0.5">
        <div className="px-2 pb-2.5 text-[10px] font-semibold uppercase tracking-widest text-white/25">Menu</div>
        {NAV_ITEMS.map((item) => {
          const active = screen === item.id
          const disabled = !!item.disabled
          return (
            <button
              key={item.id}
              onClick={() => {
                if (!disabled && item.id !== 'reports') navigate(item.id as Screen)
              }}
              disabled={disabled}
              className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all text-left
                ${active
                  ? 'bg-white/15 text-white shadow-sm'
                  : disabled
                    ? 'text-white/20 cursor-not-allowed'
                    : 'text-white/60 hover:bg-white/10 hover:text-white'
                }`}
            >
              <Ico d={ic[item.icon as keyof typeof ic]} size={15} />
              <span className="flex-1">{item.label}</span>
              {disabled && <span className="text-[10px] text-white/20 font-normal">Em breve</span>}
              {!disabled && active && <span className="size-1.5 rounded-full bg-blue-400" />}
            </button>
          )
        })}
      </nav>

      <div className="px-3 pb-4 pt-3 border-t border-white/10">
        <div className="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-white/5 cursor-pointer group transition-colors">
          <div className="size-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold shrink-0"
            style={{ fontFamily: "'DM Sans', sans-serif" }}>MO</div>
          <div className="flex-1 min-w-0">
            <div className="text-white text-xs font-medium truncate">Marina Oliveira</div>
            <div className="text-white/40 text-[10px] truncate">Segurança do Trabalho</div>
          </div>
          <Ico d={ic.logout} size={13} className="text-white/25 group-hover:text-white/50 shrink-0 transition-colors" />
        </div>
      </div>
    </aside>
  )
}

// ── Top Bar ────────────────────────────────────────────────────────────────

const PAGE_LABELS: Partial<Record<Screen, { label: string; parent?: Screen; parentLabel?: string }>> = {
  dashboard: { label: 'Dashboard' },
  employees: { label: 'Funcionários' },
  'employee-form': { label: 'Novo funcionário', parent: 'employees', parentLabel: 'Funcionários' },
  'employee-profile': { label: 'Perfil do funcionário', parent: 'employees', parentLabel: 'Funcionários' },
  trainings: { label: 'Treinamentos' },
  'training-form': { label: 'Novo treinamento', parent: 'trainings', parentLabel: 'Treinamentos' },
  'register-training': { label: 'Registrar treinamento', parent: 'employee-profile', parentLabel: 'Perfil' },
  pending: { label: 'Pendências' },
}

function TopBar({ screen, navigate }: { screen: Screen; navigate: (s: Screen) => void }) {
  const bc = PAGE_LABELS[screen]
  return (
    <header className="h-14 bg-white border-b border-slate-200 flex items-center px-6 gap-4 shrink-0">
      <div className="flex items-center gap-1.5 text-sm flex-1 min-w-0">
        {bc?.parent && (
          <>
            <button
              onClick={() => navigate(bc.parent!)}
              className="text-slate-400 hover:text-blue-600 transition-colors shrink-0"
            >
              {bc.parentLabel}
            </button>
            <Ico d={ic.chevron} size={13} className="text-slate-300 shrink-0" />
          </>
        )}
        <span className="text-slate-700 font-semibold truncate">{bc?.label}</span>
      </div>
      <div className="flex items-center gap-2 shrink-0">
        <div className="relative">
          <Ico d={ic.search} size={13} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
          <input
            type="text"
            placeholder="Buscar..."
            className="pl-8 pr-3 py-1.5 text-sm bg-slate-50 border border-slate-200 rounded-lg w-48 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-colors"
          />
        </div>
        <button className="relative p-2 rounded-lg hover:bg-slate-100 transition-colors text-slate-500 hover:text-slate-700">
          <Ico d={ic.bell} size={16} />
          <span className="absolute top-1.5 right-1.5 size-1.5 bg-red-500 rounded-full ring-1 ring-white" />
        </button>
      </div>
    </header>
  )
}

// ── App Layout ─────────────────────────────────────────────────────────────

function AppLayout({ screen, navigate, children }: { screen: Screen; navigate: (s: Screen) => void; children: React.ReactNode }) {
  return (
    <div className="flex h-full overflow-hidden" style={{ background: '#f0f4f8', fontFamily: "'Inter', sans-serif" }}>
      <Sidebar screen={screen} navigate={navigate} />
      <div className="flex-1 flex flex-col overflow-hidden">
        <TopBar screen={screen} navigate={navigate} />
        <main className="flex-1 overflow-y-auto">
          {children}
        </main>
      </div>
    </div>
  )
}

// ── Login Screen ───────────────────────────────────────────────────────────

function LoginScreen({ onLogin }: { onLogin: () => void }) {
  const [email, setEmail] = useState('marina@empresa.com')
  const [password, setPassword] = useState('••••••••')
  const [remember, setRemember] = useState(false)
  const [error, setError] = useState(false)

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    if (!email) { setError(true); return }
    setError(false)
    onLogin()
  }

  return (
    <div className="flex h-full" style={{ fontFamily: "'Inter', sans-serif" }}>
      {/* Left panel */}
      <div className="hidden lg:flex w-[42%] flex-col justify-between p-10" style={{ background: '#1e3a5f' }}>
        <div className="flex items-center gap-3">
          <div className="size-9 rounded-xl bg-blue-500 flex items-center justify-center">
            <Ico d={ic.shield} size={18} className="text-white" />
          </div>
          <span className="text-white font-bold text-lg" style={{ fontFamily: "'DM Sans', sans-serif" }}>SafeTrack</span>
        </div>

        <div className="space-y-6 max-w-xs">
          <div>
            <div className="text-blue-300/70 text-xs font-semibold uppercase tracking-widest mb-4">Sistema de Gestão</div>
            <h1 className="text-[2.4rem] font-bold text-white leading-[1.15]" style={{ fontFamily: "'DM Sans', sans-serif" }}>
              Controle de<br />Treinamentos<br />e Segurança
            </h1>
          </div>
          <p className="text-blue-200/60 text-sm leading-relaxed">
            Gerencie certificações, monitore vencimentos e garanta a conformidade da sua equipe com uma ferramenta feita para o dia a dia.
          </p>
          <div className="flex gap-8 pt-2">
            {[['48', 'Funcionários'], ['6', 'Treinamentos'], ['12', 'Alertas ativos']].map(([n, l]) => (
              <div key={l}>
                <div className="text-2xl font-bold text-white" style={{ fontFamily: "'DM Sans', sans-serif" }}>{n}</div>
                <div className="text-blue-200/50 text-xs mt-0.5">{l}</div>
              </div>
            ))}
          </div>
        </div>

        <div className="text-white/20 text-xs">© 2026 SafeTrack. Todos os direitos reservados.</div>
      </div>

      {/* Right panel */}
      <div className="flex-1 flex items-center justify-center p-8 bg-white">
        <div className="w-full max-w-sm">
          <div className="mb-8">
            <div className="flex items-center gap-2.5 mb-7 lg:hidden">
              <div className="size-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <Ico d={ic.shield} size={15} className="text-white" />
              </div>
              <span className="font-bold text-slate-800" style={{ fontFamily: "'DM Sans', sans-serif" }}>SafeTrack</span>
            </div>
            <h2 className="text-2xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Bem-vindo de volta</h2>
            <p className="text-slate-400 text-sm mt-1">Acesse sua conta para continuar</p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-4">
            {error && (
              <div className="flex items-center gap-2 px-3 py-2.5 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                <Ico d={ic.alert} size={14} className="text-red-500 shrink-0" />
                Preencha o e-mail para continuar.
              </div>
            )}
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">E-mail <span className="text-red-500">*</span></label>
              <input
                type="email"
                value={email}
                onChange={e => setEmail(e.target.value)}
                placeholder="marina@empresa.com"
                className={`w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 transition-colors
                  ${error && !email ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 focus:ring-blue-500/20 focus:border-blue-500'}`}
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">Senha</label>
              <input
                type="password"
                value={password}
                onChange={e => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
              />
            </div>
            <div className="flex items-center justify-between pt-0.5">
              <label className="flex items-center gap-2 cursor-pointer select-none">
                <input
                  type="checkbox"
                  checked={remember}
                  onChange={e => setRemember(e.target.checked)}
                  className="size-3.5 accent-blue-600 cursor-pointer"
                />
                <span className="text-xs text-slate-500">Lembrar-me</span>
              </label>
              <button type="button" className="text-xs text-blue-600 hover:underline font-medium">Esqueci minha senha</button>
            </div>
            <button
              type="submit"
              className="w-full py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors mt-1"
            >
              Entrar no sistema
            </button>
          </form>
        </div>
      </div>
    </div>
  )
}

// ── Dashboard Screen ───────────────────────────────────────────────────────

function DashboardScreen({ navigate }: { navigate: (s: Screen, opts?: { employeeId?: number }) => void }) {
  const expired = RECORDS.filter(r => r.status === 'expired')
  const expiring = RECORDS.filter(r => r.status === 'expiring')
  const valid = RECORDS.filter(r => r.status === 'valid')
  const upcoming7 = expiring.filter(r => r.daysLeft <= 7)
  const upcoming30 = expiring

  const kpis = [
    { label: 'Funcionários cadastrados', value: EMPLOYEES.length, sub: `${EMPLOYEES.filter(e => e.status === 'active').length} ativos`, bg: 'bg-[#1e3a5f]', icon: 'users' },
    { label: 'Treinamentos válidos', value: valid.length, sub: 'certificações em dia', bg: 'bg-green-600', icon: 'check' },
    { label: 'Próximos do vencimento', value: expiring.length, sub: `${upcoming7.length} vencem em até 7 dias`, bg: 'bg-amber-500', icon: 'clock' },
    { label: 'Treinamentos vencidos', value: expired.length, sub: 'requerem renovação imediata', bg: 'bg-red-600', icon: 'alert' },
  ]

  const pendingRows = [...expired, ...expiring].slice(0, 7)

  return (
    <div className="p-6 space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Visão geral</h1>
          <p className="text-slate-400 text-sm mt-0.5">30 de agosto de 2026</p>
        </div>
        <button
          onClick={() => navigate('pending')}
          className="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors"
        >
          <Ico d={ic.alert} size={14} className="text-amber-500" />
          Ver todas as pendências
        </button>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-4 gap-4">
        {kpis.map((kpi) => (
          <div key={kpi.label} className="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
            <div className={`size-9 rounded-lg ${kpi.bg} flex items-center justify-center`}>
              <Ico d={ic[kpi.icon as keyof typeof ic]} size={15} className="text-white" />
            </div>
            <div>
              <div className="text-3xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>{kpi.value}</div>
              <div className="text-xs font-medium text-slate-600 mt-0.5">{kpi.label}</div>
              <div className="text-xs text-slate-400 mt-0.5">{kpi.sub}</div>
            </div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-3 gap-5">
        {/* Pending table */}
        <div className="col-span-2 bg-white rounded-xl border border-slate-200 overflow-hidden">
          <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
              <h2 className="font-semibold text-slate-900 text-sm" style={{ fontFamily: "'DM Sans', sans-serif" }}>Pendências de treinamento</h2>
              <p className="text-xs text-slate-400 mt-0.5">{expired.length + expiring.length} pendências identificadas</p>
            </div>
            <button onClick={() => navigate('pending')} className="text-xs text-blue-600 hover:underline font-medium">
              Ver todas →
            </button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-slate-50 border-b border-slate-100">
                <tr>
                  <th className={thCls}>Funcionário</th>
                  <th className={thCls}>Treinamento</th>
                  <th className={thCls}>Validade</th>
                  <th className={thCls}>Situação</th>
                  <th className={thCls}>Ação</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {pendingRows.map((r) => (
                  <tr
                    key={r.id}
                    className={`hover:bg-slate-50/80 transition-colors ${r.status === 'expired' ? 'bg-red-50/30' : ''}`}
                  >
                    <td className={tdCls}>
                      <div className="flex items-center gap-2.5">
                        <Avatar name={r.employeeName} size="sm" />
                        <div>
                          <div className="font-medium text-slate-800 text-xs leading-tight">{r.employeeName.split(' ').slice(0, 2).join(' ')}</div>
                          <div className="text-slate-400 text-[11px]">{r.employeeRole}</div>
                        </div>
                      </div>
                    </td>
                    <td className={`${tdCls} text-xs font-medium`}>{r.trainingName}</td>
                    <td className={`${tdCls} text-xs font-mono text-slate-500`}>{r.expiryDate}</td>
                    <td className={tdCls}><StatusBadge status={r.status} /></td>
                    <td className={tdCls}>
                      <button
                        onClick={() => navigate('employee-profile', { employeeId: r.employeeId })}
                        className="text-xs text-blue-600 hover:underline font-medium"
                      >
                        Ver perfil
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Upcoming expirations */}
        <div className="bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col">
          <div className="px-5 py-4 border-b border-slate-100">
            <h2 className="font-semibold text-slate-900 text-sm" style={{ fontFamily: "'DM Sans', sans-serif" }}>Próximos vencimentos</h2>
            <p className="text-xs text-slate-400 mt-0.5">Próximos 30 dias</p>
          </div>
          <div className="divide-y divide-slate-100 flex-1">
            {upcoming30.length === 0 ? (
              <div className="px-5 py-10 text-center">
                <Ico d={ic.check} size={24} className="text-green-400 mx-auto mb-2" />
                <p className="text-sm text-slate-400">Nenhum vencimento próximo</p>
              </div>
            ) : upcoming30.map((r) => (
              <div key={r.id} className="px-5 py-3.5 hover:bg-slate-50 transition-colors">
                <div className="flex items-center justify-between gap-2">
                  <div className="min-w-0 flex-1">
                    <div className="text-xs font-semibold text-slate-800 truncate">
                      {r.employeeName.split(' ')[0]} {r.employeeName.split(' ').slice(-1)[0]}
                    </div>
                    <div className="text-[11px] text-slate-400 truncate">{r.trainingName}</div>
                  </div>
                  <span className={`shrink-0 text-xs font-bold rounded-full px-2 py-0.5 ${r.daysLeft <= 7 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'}`}>
                    {r.daysLeft}d
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}

// ── Employees Screen ──────────────────────────────────────────────────────

function EmployeesScreen({ navigate }: { navigate: (s: Screen, opts?: { employeeId?: number }) => void }) {
  const [search, setSearch] = useState('')
  const [roleFilter, setRoleFilter] = useState('')
  const [statusFilter, setStatusFilter] = useState('')

  const roles = [...new Set(EMPLOYEES.map(e => e.role))].sort()
  const filtered = EMPLOYEES.filter(e => {
    const q = search.toLowerCase()
    return (
      (e.name.toLowerCase().includes(q) || e.cpf.includes(q) || e.sector.toLowerCase().includes(q)) &&
      (!roleFilter || e.role === roleFilter) &&
      (!statusFilter || e.status === statusFilter)
    )
  })

  return (
    <div className="p-6 space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Funcionários</h1>
          <p className="text-slate-400 text-sm mt-0.5">{EMPLOYEES.length} colaboradores cadastrados</p>
        </div>
        <button
          onClick={() => navigate('employee-form')}
          className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors"
        >
          <Ico d={ic.plus} size={13} />
          Novo funcionário
        </button>
      </div>

      <div className="flex gap-3 flex-wrap">
        <div className="relative flex-1 min-w-48 max-w-xs">
          <Ico d={ic.search} size={13} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
          <input
            type="text"
            value={search}
            onChange={e => setSearch(e.target.value)}
            placeholder="Buscar por nome, CPF ou setor..."
            className="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-colors"
          />
        </div>
        <select
          value={roleFilter}
          onChange={e => setRoleFilter(e.target.value)}
          className="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
        >
          <option value="">Todos os cargos</option>
          {roles.map(r => <option key={r} value={r}>{r}</option>)}
        </select>
        <select
          value={statusFilter}
          onChange={e => setStatusFilter(e.target.value)}
          className="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
        >
          <option value="">Todas as situações</option>
          <option value="active">Ativo</option>
          <option value="inactive">Inativo</option>
        </select>
      </div>

      <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table className="w-full">
          <thead className="bg-slate-50 border-b border-slate-200">
            <tr>
              <th className={thCls}>Funcionário</th>
              <th className={thCls}>CPF</th>
              <th className={thCls}>Cargo / Setor</th>
              <th className={thCls}>Treinamentos</th>
              <th className={thCls}>Situação</th>
              <th className={thCls}>Ações</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {filtered.length === 0 ? (
              <tr>
                <td colSpan={6} className="py-16 text-center">
                  <Ico d={ic.search} size={24} className="text-slate-300 mx-auto mb-2" />
                  <p className="text-sm text-slate-400">Nenhum funcionário encontrado</p>
                  <p className="text-xs text-slate-300 mt-1">Tente ajustar os filtros de busca</p>
                </td>
              </tr>
            ) : filtered.map((e) => (
              <tr key={e.id} className="hover:bg-slate-50/80 transition-colors">
                <td className={tdCls}>
                  <div className="flex items-center gap-3">
                    <Avatar name={e.name} />
                    <div>
                      <div className="font-semibold text-slate-900 text-xs">{e.name}</div>
                      <div className="text-slate-400 text-xs">{e.email}</div>
                    </div>
                  </div>
                </td>
                <td className={`${tdCls} font-mono text-xs text-slate-500`}>{e.cpf}</td>
                <td className={tdCls}>
                  <div className="text-xs font-medium text-slate-800">{e.role}</div>
                  <div className="text-xs text-slate-400">{e.sector}</div>
                </td>
                <td className={tdCls}>
                  <div className="flex items-center gap-2 text-xs">
                    <span className="flex items-center gap-1 text-green-600 font-semibold">
                      <span className="size-1.5 rounded-full bg-green-500" />{e.trainingsValid}
                    </span>
                    {e.trainingsExpiring > 0 && (
                      <span className="flex items-center gap-1 text-amber-600 font-semibold">
                        <span className="size-1.5 rounded-full bg-amber-500" />{e.trainingsExpiring}
                      </span>
                    )}
                    {e.trainingsExpired > 0 && (
                      <span className="flex items-center gap-1 text-red-600 font-semibold">
                        <span className="size-1.5 rounded-full bg-red-500" />{e.trainingsExpired}
                      </span>
                    )}
                  </div>
                </td>
                <td className={tdCls}><EmployeeBadge status={e.status} /></td>
                <td className={tdCls}>
                  <div className="flex items-center gap-1">
                    <button
                      onClick={() => navigate('employee-profile', { employeeId: e.id })}
                      className="p-1.5 rounded-md text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                      title="Ver perfil"
                    >
                      <Ico d={ic.eye} size={14} />
                    </button>
                    <button
                      onClick={() => navigate('employee-form', { employeeId: e.id })}
                      className="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                      title="Editar"
                    >
                      <Ico d={ic.edit} size={14} />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        {filtered.length > 0 && (
          <div className="px-4 py-3 border-t border-slate-100 bg-slate-50 text-xs text-slate-400">
            Mostrando {filtered.length} de {EMPLOYEES.length} funcionários
          </div>
        )}
      </div>
    </div>
  )
}

// ── Employee Form Screen ──────────────────────────────────────────────────

function EmployeeFormScreen({ navigate }: { navigate: (s: Screen) => void }) {
  const [submitted, setSubmitted] = useState(false)

  const handleSave = () => {
    setSubmitted(true)
    setTimeout(() => navigate('employees'), 1500)
  }

  return (
    <div className="p-6 max-w-3xl">
      <div className="mb-6">
        <h1 className="text-xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Novo funcionário</h1>
        <p className="text-slate-400 text-sm mt-0.5">Preencha os dados para cadastrar um novo colaborador</p>
      </div>

      {submitted && (
        <div className="mb-5 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
          <div className="size-6 rounded-full bg-green-100 flex items-center justify-center shrink-0">
            <Ico d={ic.check} size={13} className="text-green-600" />
          </div>
          Funcionário salvo com sucesso! Redirecionando...
        </div>
      )}

      <div className="space-y-5">
        <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
          <div className="px-6 py-3.5 border-b border-slate-100 bg-slate-50/80">
            <h2 className="text-xs font-semibold text-slate-600 uppercase tracking-wider">Dados pessoais</h2>
          </div>
          <div className="p-6 grid grid-cols-2 gap-5">
            <div className="col-span-2">
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Nome completo <span className="text-red-500">*</span>
              </label>
              <input type="text" placeholder="Ex.: Carlos Eduardo Silva"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                CPF <span className="text-red-500">*</span>
              </label>
              <input type="text" placeholder="000.000.000-00"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">Data de nascimento</label>
              <input type="date"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">E-mail</label>
              <input type="email" placeholder="colaborador@empresa.com"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">Telefone</label>
              <input type="tel" placeholder="(11) 99999-9999"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
          </div>
        </div>

        <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
          <div className="px-6 py-3.5 border-b border-slate-100 bg-slate-50/80">
            <h2 className="text-xs font-semibold text-slate-600 uppercase tracking-wider">Dados profissionais</h2>
          </div>
          <div className="p-6 grid grid-cols-2 gap-5">
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Cargo <span className="text-red-500">*</span>
              </label>
              <input type="text" placeholder="Ex.: Técnico de Manutenção"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Setor <span className="text-red-500">*</span>
              </label>
              <input type="text" placeholder="Ex.: Manutenção Elétrica"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Data de admissão <span className="text-red-500">*</span>
              </label>
              <input type="date"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">Matrícula</label>
              <input type="text" placeholder="Ex.: MAT-009"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">Status do funcionário</label>
              <select className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                <option value="active">Ativo</option>
                <option value="inactive">Inativo</option>
              </select>
            </div>
          </div>
        </div>

        <div className="flex items-center justify-between pt-1">
          <p className="text-xs text-slate-400"><span className="text-red-500">*</span> Campos obrigatórios</p>
          <div className="flex items-center gap-3">
            <button
              onClick={() => navigate('employees')}
              className="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors"
            >
              Cancelar
            </button>
            <button
              onClick={handleSave}
              className="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Salvar funcionário
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}

// ── Employee Profile Screen ───────────────────────────────────────────────

function EmployeeProfileScreen({ employeeId, navigate }: { employeeId: number; navigate: (s: Screen, opts?: { employeeId?: number }) => void }) {
  const employee = EMPLOYEES.find(e => e.id === employeeId) ?? EMPLOYEES[0]
  const records = RECORDS.filter(r => r.employeeId === employee.id)

  const stats = [
    { label: 'Total', value: employee.trainingsTotal, color: 'text-slate-900' },
    { label: 'Válidos', value: employee.trainingsValid, color: 'text-green-600', bg: 'bg-green-50', border: 'border-green-100' },
    { label: 'Próximos do vencimento', value: employee.trainingsExpiring, color: 'text-amber-600', bg: 'bg-amber-50', border: 'border-amber-100' },
    { label: 'Vencidos', value: employee.trainingsExpired, color: 'text-red-600', bg: 'bg-red-50', border: 'border-red-100' },
  ]

  return (
    <div className="p-6 space-y-5">
      {/* Profile header */}
      <div className="bg-white rounded-xl border border-slate-200 p-6">
        <div className="flex items-start gap-5">
          <div className="size-16 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-700 text-2xl font-bold shrink-0"
            style={{ fontFamily: "'DM Sans', sans-serif" }}>
            {employee.name.split(' ').map((n: string) => n[0]).slice(0, 2).join('')}
          </div>
          <div className="flex-1 min-w-0">
            <div className="flex items-start justify-between gap-4">
              <div>
                <h1 className="text-xl font-bold text-slate-900 leading-tight" style={{ fontFamily: "'DM Sans', sans-serif" }}>{employee.name}</h1>
                <div className="flex items-center gap-2 mt-1.5 flex-wrap">
                  <span className="text-sm text-slate-500">{employee.role}</span>
                  <span className="text-slate-300">·</span>
                  <span className="text-sm text-slate-400">{employee.sector}</span>
                  <span className="text-slate-300">·</span>
                  <span className="text-xs font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">{employee.registration}</span>
                </div>
              </div>
              <div className="flex items-center gap-2 shrink-0">
                <EmployeeBadge status={employee.status} />
                <button
                  onClick={() => navigate('register-training', { employeeId: employee.id })}
                  className="flex items-center gap-1.5 px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors"
                >
                  <Ico d={ic.plus} size={12} />
                  Registrar treinamento
                </button>
              </div>
            </div>

            <div className="grid grid-cols-4 gap-3 mt-5 pt-5 border-t border-slate-100">
              {stats.map((s) => (
                <div key={s.label} className={`rounded-lg p-3 border ${s.bg ?? 'bg-slate-50'} ${s.border ?? 'border-slate-100'}`}>
                  <div className={`text-2xl font-bold ${s.color}`} style={{ fontFamily: "'DM Sans', sans-serif" }}>{s.value}</div>
                  <div className="text-xs text-slate-500 mt-0.5">{s.label}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Info cards */}
      <div className="grid grid-cols-2 gap-4">
        <div className="bg-white rounded-xl border border-slate-200 p-5">
          <h3 className="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Dados pessoais</h3>
          <div className="space-y-3">
            {[['CPF', employee.cpf], ['E-mail', employee.email], ['Telefone', employee.phone], ['Nascimento', employee.dob]].map(([l, v]) => (
              <div key={l} className="flex items-center justify-between text-sm gap-4">
                <span className="text-slate-400 shrink-0">{l}</span>
                <span className="text-slate-700 font-medium text-right">{v}</span>
              </div>
            ))}
          </div>
        </div>
        <div className="bg-white rounded-xl border border-slate-200 p-5">
          <h3 className="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Dados profissionais</h3>
          <div className="space-y-3">
            {[['Cargo', employee.role], ['Setor', employee.sector], ['Admissão', employee.admission], ['Matrícula', employee.registration]].map(([l, v]) => (
              <div key={l} className="flex items-center justify-between text-sm gap-4">
                <span className="text-slate-400 shrink-0">{l}</span>
                <span className="text-slate-700 font-medium text-right">{v}</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Training history */}
      <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div className="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <h2 className="font-semibold text-slate-900 text-sm" style={{ fontFamily: "'DM Sans', sans-serif" }}>Histórico de treinamentos</h2>
          <button
            onClick={() => navigate('register-training', { employeeId: employee.id })}
            className="flex items-center gap-1.5 text-xs text-blue-600 hover:underline font-medium"
          >
            <Ico d={ic.plus} size={12} />
            Registrar treinamento
          </button>
        </div>
        {records.length === 0 ? (
          <div className="py-14 text-center">
            <Ico d={ic.book} size={28} className="text-slate-200 mx-auto mb-3" />
            <p className="text-sm text-slate-400 font-medium">Nenhum treinamento registrado</p>
            <p className="text-xs text-slate-300 mt-1">Clique em "Registrar treinamento" para adicionar</p>
          </div>
        ) : (
          <table className="w-full">
            <thead className="bg-slate-50 border-b border-slate-100">
              <tr>
                <th className={thCls}>Treinamento</th>
                <th className={thCls}>Data de realização</th>
                <th className={thCls}>Data de validade</th>
                <th className={thCls}>Situação</th>
                <th className={thCls}>Ações</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {records.map((r) => (
                <tr key={r.id} className={`hover:bg-slate-50 transition-colors ${r.status === 'expired' ? 'bg-red-50/20' : ''}`}>
                  <td className={`${tdCls} font-semibold text-xs text-slate-800`}>{r.trainingName}</td>
                  <td className={`${tdCls} font-mono text-xs text-slate-500`}>{r.completedDate}</td>
                  <td className={`${tdCls} font-mono text-xs text-slate-500`}>{r.expiryDate}</td>
                  <td className={tdCls}><StatusBadge status={r.status} /></td>
                  <td className={tdCls}>
                    <button className="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                      <Ico d={ic.edit} size={13} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </div>
  )
}

// ── Trainings Screen ──────────────────────────────────────────────────────

function TrainingsScreen({ navigate }: { navigate: (s: Screen) => void }) {
  return (
    <div className="p-6 space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Treinamentos</h1>
          <p className="text-slate-400 text-sm mt-0.5">{TRAININGS.length} tipos de treinamento cadastrados</p>
        </div>
        <button
          onClick={() => navigate('training-form')}
          className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors"
        >
          <Ico d={ic.plus} size={13} />
          Novo treinamento
        </button>
      </div>

      <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table className="w-full">
          <thead className="bg-slate-50 border-b border-slate-200">
            <tr>
              <th className={thCls}>Nome do treinamento</th>
              <th className={thCls}>Descrição</th>
              <th className={thCls}>C.H.</th>
              <th className={thCls}>Validade</th>
              <th className={thCls}>Funcionários</th>
              <th className={thCls}>Status</th>
              <th className={thCls}>Ações</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {TRAININGS.map((t) => (
              <tr key={t.id} className="hover:bg-slate-50/80 transition-colors">
                <td className={tdCls}>
                  <div className="font-semibold text-slate-900 text-xs">{t.name}</div>
                </td>
                <td className={`${tdCls} text-xs text-slate-400 max-w-xs`}>
                  <span className="line-clamp-2 leading-relaxed">{t.description}</span>
                </td>
                <td className={`${tdCls} text-xs font-medium`}>{t.hours}h</td>
                <td className={`${tdCls} text-xs`}>{t.validityMonths} meses</td>
                <td className={`${tdCls}`}>
                  <span className="inline-flex items-center justify-center size-7 rounded-full bg-slate-100 text-xs font-bold text-slate-700">{t.employeeCount}</span>
                </td>
                <td className={tdCls}>
                  {t.status === 'active'
                    ? <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20"><span className="size-1.5 rounded-full bg-green-500" />Ativo</span>
                    : <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-500 ring-1 ring-inset ring-slate-300/60"><span className="size-1.5 rounded-full bg-slate-400" />Inativo</span>
                  }
                </td>
                <td className={tdCls}>
                  <div className="flex items-center gap-1">
                    <button className="p-1.5 rounded-md text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Editar">
                      <Ico d={ic.edit} size={13} />
                    </button>
                    <button className="p-1.5 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Excluir">
                      <Ico d={ic.trash} size={13} />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

// ── Training Form Screen ──────────────────────────────────────────────────

function TrainingFormScreen({ navigate }: { navigate: (s: Screen) => void }) {
  return (
    <div className="p-6 max-w-2xl">
      <div className="mb-6">
        <h1 className="text-xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Novo treinamento</h1>
        <p className="text-slate-400 text-sm mt-0.5">Cadastre um novo tipo de treinamento no sistema</p>
      </div>

      <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div className="px-6 py-3.5 border-b border-slate-100 bg-slate-50/80">
          <h2 className="text-xs font-semibold text-slate-600 uppercase tracking-wider">Informações do treinamento</h2>
        </div>
        <div className="p-6 space-y-5">
          <div>
            <label className="block text-xs font-semibold text-slate-600 mb-1.5">
              Nome do treinamento <span className="text-red-500">*</span>
            </label>
            <input type="text" placeholder="Ex.: Trabalho em Altura"
              className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
          </div>
          <div>
            <label className="block text-xs font-semibold text-slate-600 mb-1.5">Descrição</label>
            <textarea rows={3} placeholder="Descreva o objetivo e o conteúdo abordado no treinamento..."
              className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors resize-none" />
          </div>
          <div className="grid grid-cols-2 gap-5">
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Carga horária (horas) <span className="text-red-500">*</span>
              </label>
              <input type="number" placeholder="8" min="1"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Validade (meses) <span className="text-red-500">*</span>
              </label>
              <input type="number" placeholder="12" min="1"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
          </div>
          <div>
            <label className="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
            <select className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
              <option value="active">Ativo</option>
              <option value="inactive">Inativo</option>
            </select>
          </div>
        </div>
      </div>

      <div className="flex items-center justify-end gap-3 mt-5">
        <button onClick={() => navigate('trainings')} className="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Cancelar</button>
        <button className="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Salvar treinamento</button>
      </div>
    </div>
  )
}

// ── Register Training Screen ──────────────────────────────────────────────

function RegisterTrainingScreen({ employeeId, navigate }: { employeeId: number; navigate: (s: Screen, opts?: { employeeId?: number }) => void }) {
  const employee = EMPLOYEES.find(e => e.id === employeeId) ?? EMPLOYEES[0]
  const [dragOver, setDragOver] = useState(false)
  const [hasFile, setHasFile] = useState(false)

  return (
    <div className="p-6 max-w-2xl">
      <div className="mb-6">
        <h1 className="text-xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Registrar treinamento</h1>
        <p className="text-slate-400 text-sm mt-0.5">Registre a realização de um treinamento para o colaborador</p>
      </div>

      <div className="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 flex items-center gap-3">
        <Avatar name={employee.name} />
        <div className="flex-1 min-w-0">
          <div className="text-sm font-semibold text-blue-900 truncate">{employee.name}</div>
          <div className="text-xs text-blue-500 truncate">{employee.role} — {employee.sector}</div>
        </div>
        <span className="text-xs font-mono text-blue-400 shrink-0">{employee.registration}</span>
      </div>

      <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div className="px-6 py-3.5 border-b border-slate-100 bg-slate-50/80">
          <h2 className="text-xs font-semibold text-slate-600 uppercase tracking-wider">Dados do treinamento realizado</h2>
        </div>
        <div className="p-6 space-y-5">
          <div>
            <label className="block text-xs font-semibold text-slate-600 mb-1.5">
              Treinamento <span className="text-red-500">*</span>
            </label>
            <select className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
              <option value="">Selecione o treinamento...</option>
              {TRAININGS.map(t => <option key={t.id} value={t.id}>{t.name} ({t.validityMonths} meses)</option>)}
            </select>
          </div>
          <div className="grid grid-cols-2 gap-5">
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Data de realização <span className="text-red-500">*</span>
              </label>
              <input type="date"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1.5">
                Data de validade <span className="text-red-500">*</span>
              </label>
              <input type="date"
                className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>
          </div>
          <div>
            <label className="block text-xs font-semibold text-slate-600 mb-1.5">Observações</label>
            <textarea rows={3} placeholder="Informações adicionais sobre o treinamento realizado..."
              className="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors resize-none" />
          </div>

          <div>
            <label className="block text-xs font-semibold text-slate-600 mb-1.5">Certificado</label>
            {hasFile ? (
              <div className="flex items-center gap-3 p-4 border border-green-200 bg-green-50 rounded-xl">
                <div className="size-8 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                  <Ico d={ic.check} size={14} className="text-green-600" />
                </div>
                <div className="flex-1 min-w-0">
                  <div className="text-xs font-semibold text-green-800">certificado_trabalho_altura.pdf</div>
                  <div className="text-[11px] text-green-600">245 KB — Pronto para envio</div>
                </div>
                <button onClick={() => setHasFile(false)} className="p-1 rounded text-green-400 hover:text-green-700 transition-colors">
                  <Ico d={ic.x} size={13} />
                </button>
              </div>
            ) : (
              <div
                onClick={() => setHasFile(true)}
                onDragOver={(e) => { e.preventDefault(); setDragOver(true) }}
                onDragLeave={() => setDragOver(false)}
                onDrop={(e) => { e.preventDefault(); setDragOver(false); setHasFile(true) }}
                className={`border-2 border-dashed rounded-xl p-8 text-center transition-colors cursor-pointer select-none
                  ${dragOver ? 'border-blue-400 bg-blue-50' : 'border-slate-200 hover:border-blue-300 hover:bg-slate-50/80'}`}
              >
                <div className="flex flex-col items-center gap-2">
                  <div className={`size-10 rounded-xl flex items-center justify-center transition-colors ${dragOver ? 'bg-blue-100' : 'bg-slate-100'}`}>
                    <Ico d={ic.upload} size={18} className={dragOver ? 'text-blue-500' : 'text-slate-400'} />
                  </div>
                  <div>
                    <div className="text-sm font-medium text-slate-600">Arraste o certificado aqui</div>
                    <div className="text-xs text-slate-400 mt-0.5">ou <span className="text-blue-600 underline">clique para selecionar</span></div>
                  </div>
                  <div className="text-xs text-slate-300">PDF, JPG ou PNG — máximo 10 MB</div>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>

      <div className="flex items-center justify-end gap-3 mt-5">
        <button
          onClick={() => navigate('employee-profile', { employeeId: employee.id })}
          className="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors"
        >
          Cancelar
        </button>
        <button className="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
          Registrar treinamento
        </button>
      </div>
    </div>
  )
}

// ── Pending Screen ────────────────────────────────────────────────────────

function PendingScreen({ navigate }: { navigate: (s: Screen, opts?: { employeeId?: number }) => void }) {
  const [filter, setFilter] = useState<'all' | 'expired' | 'expiring'>('all')
  const [sectorFilter, setSectorFilter] = useState('')
  const [trainingFilter, setTrainingFilter] = useState('')

  const allPending = RECORDS.filter(r => r.status !== 'valid')
  const expired = RECORDS.filter(r => r.status === 'expired')
  const expiring = RECORDS.filter(r => r.status === 'expiring')
  const in7 = expiring.filter(r => r.daysLeft <= 7)
  const in30 = expiring

  const sectors = [...new Set(RECORDS.map(r => r.employeeSector))].sort()
  const trainingNames = [...new Set(RECORDS.map(r => r.trainingName))].sort()

  const source = filter === 'expired' ? expired : filter === 'expiring' ? expiring : allPending
  const displayed = source.filter(r =>
    (!sectorFilter || r.employeeSector === sectorFilter) &&
    (!trainingFilter || r.trainingName === trainingFilter)
  )

  return (
    <div className="p-6 space-y-5">
      <div>
        <h1 className="text-xl font-bold text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>Pendências</h1>
        <p className="text-slate-400 text-sm mt-0.5">Treinamentos vencidos ou com renovação próxima</p>
      </div>

      {/* Summary cards */}
      <div className="grid grid-cols-3 gap-4">
        <div className="rounded-xl border border-red-200 p-5" style={{ background: '#fff5f5' }}>
          <div className="flex items-center gap-2.5 mb-3">
            <div className="size-8 rounded-lg bg-red-600 flex items-center justify-center shrink-0">
              <Ico d={ic.x} size={13} className="text-white" />
            </div>
            <span className="text-sm font-semibold text-red-800">Vencidos</span>
          </div>
          <div className="text-3xl font-bold text-red-700" style={{ fontFamily: "'DM Sans', sans-serif" }}>{expired.length}</div>
          <div className="text-xs text-red-400 mt-1">renovação imediata necessária</div>
        </div>
        <div className="rounded-xl border border-amber-200 p-5" style={{ background: '#fffbeb' }}>
          <div className="flex items-center gap-2.5 mb-3">
            <div className="size-8 rounded-lg bg-amber-500 flex items-center justify-center shrink-0">
              <Ico d={ic.alert} size={13} className="text-white" />
            </div>
            <span className="text-sm font-semibold text-amber-800">Vencem em 7 dias</span>
          </div>
          <div className="text-3xl font-bold text-amber-700" style={{ fontFamily: "'DM Sans', sans-serif" }}>{in7.length}</div>
          <div className="text-xs text-amber-400 mt-1">ação urgente recomendada</div>
        </div>
        <div className="rounded-xl border border-orange-200 p-5" style={{ background: '#fff7ed' }}>
          <div className="flex items-center gap-2.5 mb-3">
            <div className="size-8 rounded-lg bg-orange-500 flex items-center justify-center shrink-0">
              <Ico d={ic.clock} size={13} className="text-white" />
            </div>
            <span className="text-sm font-semibold text-orange-800">Vencem em 30 dias</span>
          </div>
          <div className="text-3xl font-bold text-orange-700" style={{ fontFamily: "'DM Sans', sans-serif" }}>{in30.length}</div>
          <div className="text-xs text-orange-400 mt-1">agendar renovação</div>
        </div>
      </div>

      {/* Filter bar */}
      <div className="flex items-center gap-3 flex-wrap">
        <div className="flex items-center bg-white border border-slate-200 rounded-lg p-1 gap-0.5">
          {([['all', `Todos (${allPending.length})`], ['expired', `Vencidos (${expired.length})`], ['expiring', `Próximos (${expiring.length})`]] as const).map(([val, label]) => (
            <button
              key={val}
              onClick={() => setFilter(val)}
              className={`px-3 py-1.5 text-xs font-semibold rounded-md transition-all ${filter === val ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100'}`}
            >
              {label}
            </button>
          ))}
        </div>
        <select
          value={sectorFilter}
          onChange={e => setSectorFilter(e.target.value)}
          className="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
        >
          <option value="">Todos os setores</option>
          {sectors.map(s => <option key={s} value={s}>{s}</option>)}
        </select>
        <select
          value={trainingFilter}
          onChange={e => setTrainingFilter(e.target.value)}
          className="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
        >
          <option value="">Todos os treinamentos</option>
          {trainingNames.map(t => <option key={t} value={t}>{t}</option>)}
        </select>
        {(sectorFilter || trainingFilter) && (
          <button
            onClick={() => { setSectorFilter(''); setTrainingFilter('') }}
            className="flex items-center gap-1 text-xs text-slate-400 hover:text-slate-600 transition-colors"
          >
            <Ico d={ic.x} size={12} />
            Limpar filtros
          </button>
        )}
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table className="w-full">
          <thead className="bg-slate-50 border-b border-slate-200">
            <tr>
              <th className={thCls}>Funcionário</th>
              <th className={thCls}>Treinamento</th>
              <th className={thCls}>Setor</th>
              <th className={thCls}>Realização</th>
              <th className={thCls}>Validade</th>
              <th className={thCls}>Dias restantes</th>
              <th className={thCls}>Situação</th>
              <th className={thCls}>Ação</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {displayed.length === 0 ? (
              <tr>
                <td colSpan={8} className="py-14 text-center">
                  <Ico d={ic.check} size={28} className="text-slate-200 mx-auto mb-2" />
                  <p className="text-sm text-slate-400">Nenhuma pendência encontrada</p>
                  <p className="text-xs text-slate-300 mt-1">Tente ajustar os filtros</p>
                </td>
              </tr>
            ) : displayed.map((r) => (
              <tr
                key={r.id}
                className={`hover:bg-slate-50/80 transition-colors ${r.status === 'expired' ? 'bg-red-50/25' : ''}`}
              >
                <td className={tdCls}>
                  <div className="flex items-center gap-2.5">
                    <Avatar name={r.employeeName} size="sm" />
                    <div>
                      <div className="font-semibold text-slate-900 text-xs">{r.employeeName.split(' ').slice(0, 2).join(' ')}</div>
                      <div className="text-[11px] text-slate-400">{r.employeeRole}</div>
                    </div>
                  </div>
                </td>
                <td className={`${tdCls} text-xs font-medium text-slate-800`}>{r.trainingName}</td>
                <td className={`${tdCls} text-xs text-slate-500`}>{r.employeeSector}</td>
                <td className={`${tdCls} font-mono text-xs text-slate-400`}>{r.completedDate}</td>
                <td className={`${tdCls} font-mono text-xs text-slate-500`}>{r.expiryDate}</td>
                <td className={tdCls}>
                  <span className={`text-xs font-bold ${r.daysLeft < 0 ? 'text-red-600' : r.daysLeft <= 7 ? 'text-amber-600' : 'text-slate-500'}`}>
                    {r.daysLeft < 0 ? `${Math.abs(r.daysLeft)}d atraso` : `${r.daysLeft}d`}
                  </span>
                </td>
                <td className={tdCls}><StatusBadge status={r.status} /></td>
                <td className={tdCls}>
                  <button
                    onClick={() => navigate('employee-profile', { employeeId: r.employeeId })}
                    className="text-xs text-blue-600 hover:underline font-medium whitespace-nowrap"
                  >
                    Ver perfil →
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        {displayed.length > 0 && (
          <div className="px-4 py-3 border-t border-slate-100 bg-slate-50 text-xs text-slate-400">
            {displayed.length} {displayed.length === 1 ? 'pendência' : 'pendências'} encontrada{displayed.length !== 1 ? 's' : ''}
          </div>
        )}
      </div>
    </div>
  )
}

// ── Root App ──────────────────────────────────────────────────────────────

export default function App() {
  const [screen, setScreen] = useState<Screen>('login')
  const [selectedEmployeeId, setSelectedEmployeeId] = useState<number>(1)

  const navigate = (s: Screen, opts?: { employeeId?: number }) => {
    if (opts?.employeeId !== undefined) setSelectedEmployeeId(opts.employeeId)
    setScreen(s)
  }

  if (screen === 'login') {
    return <LoginScreen onLogin={() => navigate('dashboard')} />
  }

  return (
    <AppLayout screen={screen} navigate={navigate}>
      {screen === 'dashboard' && <DashboardScreen navigate={navigate} />}
      {screen === 'employees' && <EmployeesScreen navigate={navigate} />}
      {screen === 'employee-form' && <EmployeeFormScreen navigate={navigate} />}
      {screen === 'employee-profile' && <EmployeeProfileScreen employeeId={selectedEmployeeId} navigate={navigate} />}
      {screen === 'trainings' && <TrainingsScreen navigate={navigate} />}
      {screen === 'training-form' && <TrainingFormScreen navigate={navigate} />}
      {screen === 'register-training' && <RegisterTrainingScreen employeeId={selectedEmployeeId} navigate={navigate} />}
      {screen === 'pending' && <PendingScreen navigate={navigate} />}
    </AppLayout>
  )
}
