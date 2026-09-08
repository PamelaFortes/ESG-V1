<?php
$users = [
    ['email'=>'marina@empresa.com',         'password'=>'gestor123',  'role'=>'gestor',      'name'=>'Marina Oliveira',       'emp_id'=>null],
    ['email'=>'rh@empresa.com',             'password'=>'gestor123',  'role'=>'gestor',      'name'=>'Ricardo Henrique',      'emp_id'=>null],
    ['email'=>'carlos.silva@empresa.com',   'password'=>'func123',    'role'=>'funcionario', 'name'=>'Carlos Eduardo Silva',  'emp_id'=>1],
    ['email'=>'ana.rodrigues@empresa.com',  'password'=>'func123',    'role'=>'funcionario', 'name'=>'Ana Paula Rodrigues',   'emp_id'=>2],
    ['email'=>'roberto.santos@empresa.com', 'password'=>'func123',    'role'=>'funcionario', 'name'=>'Roberto Ferreira Santos','emp_id'=>3],
    ['email'=>'juliana.costa@empresa.com',  'password'=>'func123',    'role'=>'funcionario', 'name'=>'Juliana Oliveira Costa','emp_id'=>4],
    ['email'=>'marcos.lima@empresa.com',    'password'=>'func123',    'role'=>'funcionario', 'name'=>'Marcos Antônio Lima',   'emp_id'=>5],
    ['email'=>'paulo.alves@empresa.com',    'password'=>'func123',    'role'=>'funcionario', 'name'=>'Paulo Henrique Alves',  'emp_id'=>7],
    ['email'=>'sandra.pinto@empresa.com',   'password'=>'func123',    'role'=>'funcionario', 'name'=>'Sandra Regina Pinto',   'emp_id'=>8],
];
$employees = [
    ['id'=>1,'name'=>'Carlos Eduardo Silva','cpf'=>'123.456.789-01','role'=>'Técnico de Manutenção','sector'=>'Manutenção Elétrica','email'=>'carlos.silva@empresa.com','phone'=>'(11) 98765-4321','dob'=>'15/03/1985','admission'=>'10/02/2020','registration'=>'MAT-001','status'=>'active','t_total'=>6,'t_valid'=>3,'t_expiring'=>2,'t_expired'=>1],
    ['id'=>2,'name'=>'Ana Paula Rodrigues','cpf'=>'234.567.890-12','role'=>'Supervisora de Limpeza','sector'=>'Conservação','email'=>'ana.rodrigues@empresa.com','phone'=>'(11) 97654-3210','dob'=>'22/07/1990','admission'=>'15/05/2019','registration'=>'MAT-002','status'=>'active','t_total'=>5,'t_valid'=>5,'t_expiring'=>0,'t_expired'=>0],
    ['id'=>3,'name'=>'Roberto Ferreira Santos','cpf'=>'345.678.901-23','role'=>'Eletricista','sector'=>'Manutenção Elétrica','email'=>'roberto.santos@empresa.com','phone'=>'(11) 96543-2109','dob'=>'08/11/1978','admission'=>'20/08/2018','registration'=>'MAT-003','status'=>'active','t_total'=>7,'t_valid'=>2,'t_expiring'=>1,'t_expired'=>4],
    ['id'=>4,'name'=>'Juliana Oliveira Costa','cpf'=>'456.789.012-34','role'=>'Técnica de Segurança','sector'=>'Segurança do Trabalho','email'=>'juliana.costa@empresa.com','phone'=>'(11) 95432-1098','dob'=>'30/04/1992','admission'=>'05/01/2021','registration'=>'MAT-004','status'=>'active','t_total'=>8,'t_valid'=>8,'t_expiring'=>0,'t_expired'=>0],
    ['id'=>5,'name'=>'Marcos Antônio Lima','cpf'=>'567.890.123-45','role'=>'Pedreiro','sector'=>'Obras Civis','email'=>'marcos.lima@empresa.com','phone'=>'(11) 94321-0987','dob'=>'14/09/1982','admission'=>'12/03/2017','registration'=>'MAT-005','status'=>'active','t_total'=>4,'t_valid'=>1,'t_expiring'=>1,'t_expired'=>2],
    ['id'=>6,'name'=>'Fernanda Souza Mendes','cpf'=>'678.901.234-56','role'=>'Auxiliar de Manutenção','sector'=>'Manutenção Geral','email'=>'fernanda.mendes@empresa.com','phone'=>'(11) 93210-9876','dob'=>'01/12/1995','admission'=>'01/06/2022','registration'=>'MAT-006','status'=>'inactive','t_total'=>3,'t_valid'=>2,'t_expiring'=>0,'t_expired'=>1],
    ['id'=>7,'name'=>'Paulo Henrique Alves','cpf'=>'789.012.345-67','role'=>'Encanador','sector'=>'Manutenção Hidráulica','email'=>'paulo.alves@empresa.com','phone'=>'(11) 92109-8765','dob'=>'25/06/1988','admission'=>'15/11/2020','registration'=>'MAT-007','status'=>'active','t_total'=>5,'t_valid'=>2,'t_expiring'=>2,'t_expired'=>1],
    ['id'=>8,'name'=>'Sandra Regina Pinto','cpf'=>'890.123.456-78','role'=>'Operadora de Limpeza','sector'=>'Conservação','email'=>'sandra.pinto@empresa.com','phone'=>'(11) 91098-7654','dob'=>'17/01/1983','admission'=>'04/07/2016','registration'=>'MAT-008','status'=>'active','t_total'=>4,'t_valid'=>4,'t_expiring'=>0,'t_expired'=>0],
];

$trainings = [
    ['id'=>1,'name'=>'Integração de Segurança','desc'=>'Treinamento inicial sobre normas e procedimentos de segurança da empresa','hours'=>8,'validity'=>12,'status'=>'active','employees'=>42],
    ['id'=>2,'name'=>'Trabalho em Altura','desc'=>'NR-35 — Capacitação para atividades realizadas acima de 2 metros do nível inferior','hours'=>16,'validity'=>12,'status'=>'active','employees'=>18],
    ['id'=>3,'name'=>'Segurança com Ferramentas','desc'=>'Uso correto e seguro de ferramentas manuais e elétricas no trabalho','hours'=>4,'validity'=>24,'status'=>'active','employees'=>35],
    ['id'=>4,'name'=>'Primeiros Socorros','desc'=>'Procedimentos básicos de primeiros socorros, incluindo RCP e uso do DEA','hours'=>8,'validity'=>24,'status'=>'active','employees'=>12],
    ['id'=>5,'name'=>'Uso de EPI','desc'=>'Identificação, uso correto e conservação de Equipamentos de Proteção Individual','hours'=>4,'validity'=>12,'status'=>'active','employees'=>48],
    ['id'=>6,'name'=>'Elétrica de Baixa Tensão','desc'=>'NR-10 — Segurança em instalações e serviços em eletricidade de baixa tensão','hours'=>40,'validity'=>24,'status'=>'active','employees'=>10],
];

$records = [
    ['id'=>1, 'emp_id'=>3,'emp_name'=>'Roberto Ferreira Santos','emp_role'=>'Eletricista','emp_sector'=>'Manutenção Elétrica','training'=>'Integração de Segurança','done'=>'01/08/2025','expiry'=>'01/08/2026','status'=>'expired','days'=>-29],
    ['id'=>2, 'emp_id'=>3,'emp_name'=>'Roberto Ferreira Santos','emp_role'=>'Eletricista','emp_sector'=>'Manutenção Elétrica','training'=>'Trabalho em Altura','done'=>'15/07/2025','expiry'=>'15/07/2026','status'=>'expired','days'=>-46],
    ['id'=>3, 'emp_id'=>5,'emp_name'=>'Marcos Antônio Lima','emp_role'=>'Pedreiro','emp_sector'=>'Obras Civis','training'=>'Trabalho em Altura','done'=>'20/07/2025','expiry'=>'20/07/2026','status'=>'expired','days'=>-41],
    ['id'=>4, 'emp_id'=>7,'emp_name'=>'Paulo Henrique Alves','emp_role'=>'Encanador','emp_sector'=>'Manutenção Hidráulica','training'=>'Uso de EPI','done'=>'10/08/2025','expiry'=>'10/08/2026','status'=>'expired','days'=>-20],
    ['id'=>5, 'emp_id'=>1,'emp_name'=>'Carlos Eduardo Silva','emp_role'=>'Técnico de Manutenção','emp_sector'=>'Manutenção Elétrica','training'=>'Uso de EPI','done'=>'05/09/2025','expiry'=>'05/09/2026','status'=>'expiring','days'=>6],
    ['id'=>6, 'emp_id'=>7,'emp_name'=>'Paulo Henrique Alves','emp_role'=>'Encanador','emp_sector'=>'Manutenção Hidráulica','training'=>'Integração de Segurança','done'=>'12/09/2025','expiry'=>'12/09/2026','status'=>'expiring','days'=>13],
    ['id'=>7, 'emp_id'=>1,'emp_name'=>'Carlos Eduardo Silva','emp_role'=>'Técnico de Manutenção','emp_sector'=>'Manutenção Elétrica','training'=>'Segurança com Ferramentas','done'=>'25/09/2024','expiry'=>'25/09/2026','status'=>'expiring','days'=>26],
    ['id'=>8, 'emp_id'=>5,'emp_name'=>'Marcos Antônio Lima','emp_role'=>'Pedreiro','emp_sector'=>'Obras Civis','training'=>'Uso de EPI','done'=>'28/09/2025','expiry'=>'28/09/2026','status'=>'expiring','days'=>29],
    ['id'=>9, 'emp_id'=>2,'emp_name'=>'Ana Paula Rodrigues','emp_role'=>'Supervisora de Limpeza','emp_sector'=>'Conservação','training'=>'Integração de Segurança','done'=>'01/10/2025','expiry'=>'01/10/2026','status'=>'valid','days'=>32],
    ['id'=>10,'emp_id'=>4,'emp_name'=>'Juliana Oliveira Costa','emp_role'=>'Técnica de Segurança','emp_sector'=>'Segurança do Trabalho','training'=>'Primeiros Socorros','done'=>'15/06/2024','expiry'=>'15/06/2027','status'=>'valid','days'=>654],
    ['id'=>11,'emp_id'=>8,'emp_name'=>'Sandra Regina Pinto','emp_role'=>'Operadora de Limpeza','emp_sector'=>'Conservação','training'=>'Uso de EPI','done'=>'20/11/2025','expiry'=>'20/11/2026','status'=>'valid','days'=>82],
    ['id'=>12,'emp_id'=>1,'emp_name'=>'Carlos Eduardo Silva','emp_role'=>'Técnico de Manutenção','emp_sector'=>'Manutenção Elétrica','training'=>'Elétrica de Baixa Tensão','done'=>'10/02/2025','expiry'=>'10/02/2027','status'=>'valid','days'=>529],
    ['id'=>13,'emp_id'=>4,'emp_name'=>'Juliana Oliveira Costa','emp_role'=>'Técnica de Segurança','emp_sector'=>'Segurança do Trabalho','training'=>'Trabalho em Altura','done'=>'20/03/2025','expiry'=>'20/03/2026','status'=>'expired','days'=>-163],
    ['id'=>14,'emp_id'=>3,'emp_name'=>'Roberto Ferreira Santos','emp_role'=>'Eletricista','emp_sector'=>'Manutenção Elétrica','training'=>'Elétrica de Baixa Tensão','done'=>'05/04/2024','expiry'=>'05/04/2026','status'=>'expired','days'=>-147],
];