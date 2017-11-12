$(function() {
  var availableTags = [
    "ABREU, Abelardo Gomes de",
    "FIALHO, Leonardo Stucker",
    "FILIPPO, Juan Carlos Di",
    "ABREU JÚNIOR, Júlio de",
    "ABREU, joão Clodomiro de",
    "ABREU, José Coelho da Gama e",
    "ADORNO, Álvaro Rodrigues ",
    "ADORNO, José",
    "AFLALO FILHO, Roberto",
    "AFLALO, Roberto",
    "AGUIAR, Francisco Marcelino de Souza",
    "AGUIAR, Walmir Santos",
    "Albuquerque & Longo",
    "ALBUQUERQUE, Alexandre",
    "ALMEIDA, Bernardino Sena Reis e",
    "ALMEIDA, Carlos Luís de",
    "ALPOIM, José Fernandes Pinto",
    "ÁLVARES, Padre João",
    "ALVES, Hermillo Cândido da Costa",
    "AMARAL, Ângelo Thomaz do",
    "AMARAL, Walmyr Lima",
    "ANCHIETA, José de",
    "ANDRADA, Martim Francisco Ribeiro de",
    "ANHAIA MELLO, Luiz Ignácio Romeiro de",
    "ANJOS, Frei Rodrigo dos",
    "ANNES, Álvaro",
    "ANTIQUEIRA, Domingos de Castro",
    "Antônio Menéres Planejamento e Arquitetura",
    "ARAÚJO, Cláudio Luiz",
    "ARTIGAS, João Batista Vilanova",
    "ARZÃO, Cornélio",
    "ASCENÇÃO, Frei Mateus da",
    "AZAMBUJA, Ary Fontoura de",
    "Azevedo & Travassos S/A",
    "AZEVEDO, Antonio Mariano de",
    "AZEVEDO, Orlando",
    "AZEVEDO, Thomás Antonio de",
    "BADRA JÚNIOR, Miguel",
    "BAHIANA, Elisário Antônio da Cunha",
    "BAHIANA, Elisiário Antonio da Cunha ",
    "Banco Nacional da Habitação (BNH)",
    "BARBOSA, José Caetano Horta",
    "BARDI, Achillina Bo",
    "BARROS, Ary de Queiroz",
    "BARROS, Luiz Antonio Recamán",
    "BARTALINI, Vladimir",
    "BASTIDE, H.",
    "BASTOS, Pedro Paulo Bernardes",
    "BECK, Francisco",
    "BECKER, Alfredo Ernesto",
    "BELLUCCI, José Augusto e José Carlos ",
    "BENNATON, Newton",
    "BENTO, Joaquim Ferreira",
    "BERGAMIN, Antônio Sérgio",
    "BERNARDES, Sérgio Wladimir",
    "BERRONE, João Emanuel",
    "BERTOLDI, Bianchi",
    "BLOEM, João",
    "BÖHM, Gottfried",
    "BOLONHA, Francisco de Paula Lemos",
    "BONFIM JÚNIOR, Léo",
    "BONINI, Cristóforo",
    "BONINI, Cristóvão",
    "BOTELHO, Álvaro Carlos de Arruda",
    "BOTTI, Carlos Amélio",
    "BRAGA, Diogo",
    "BRANCO, Pedro Pereira Rio",
    "BRATKE, Oswaldo Arthur",
    "BRAZ, Padre Afonso",
    "BRAZIL, Álvaro Vital",
    "BRERETON, Robert Pearson",
    "BRESSER, Carlos Abrão",
    "BRITO, Fernando Saturnino de",
    "BRITO, Francisco Saturnino Rodrigues de",
    "BRUNA, Paulo Júlio Valentino",
    "BRUNLESS, James",
    "BUARRAJ, Munir",
    "BUSSAB, Sami",
    "CAIUBY, Nestor Dale",
    "CAIUBY, Olavo Franco",
    "CALHEIROS, Antônio Pereira Sousa",
    "CAMARGO, Mauro Álvaro de Souza",
    "CAMERON, John",
    "Campbell e Projetos Arquitetos Associados",
    "CAMPELLO, Glauco",
    "CAMPOS FILHO, Cândido Malta",
    "CAMPOS, Antônio José de ",
    "CANDIA, Salvador",
    "CARDIM FILHO, Carlos Alberto Gomes",
    "CARDOSO, Lourenço",
    "CARMELO, Frei Jesuíno do Monte",
    "CARNEIRO, Antonio Dias",
    "CARODOZO, Roberto Coelho",
    "CARVALHO, Adelmar da Costa",
    "CARVALHO, Flávio Resende de ",
    "CARVALHO, Francisco Antônio Pereira de",
    "CARVALHO, Osmar",
    "Casa de Projetos Arquitetos Associados",
    "CASCALDI, Carlos",
    "CASÉ, Paulo Hamilton",
    "CASTRO, André Álvares de",
    "CAVALCANTI, Hélio Uchoa",
    "CELLIGOI, Jorge",
    "CERQUEIRA, Francisco de Lima",
    "CÉSAR, Roberto Cerqueira",
    "CHAGAS, José Plínio",
    "CIAMPAGLIA, Galiano",
    "CIANCIARULLO, Cláudio",
    "COIMBRA, Frei Francisco de",
    "COLFOSCO, João Antônio Luiz Carrara",
    "COMAS, Carlos Eduardo Dias",
    "Companhia Auxiliar de Serviços de Administração – CASA",
    "Companhia Metropolitana de Habitação Ltda.",
    "Companhia Metropolitana de São Paulo",
    "CONDE, Luiz Paulo Fernandez",
    "Construtora Adolpho Lindenberg",
    "Construtora Albuquerque Takaoka",
    "Construtora Metropolitana de Habitação de SP",
    "Construtora Warchavchick-Newmann Ltda.",
    "CORONA, Eduardo ",
    "CÔRREA, Manoel Kosciuszko Pereira da Silva",
    "COSTA, João José da Silva",
    "COSTA, Lúcio Marçal Ferreira Ribeiro de Lima e",
    "COSTA, Rufino José Felizardo e",
    "CRISTOFANI, Telesforo Giorgio",
    "CROCE, Plínio",
    "CUNHA, Adhemar Marinho",
    "D' ORDON, Robert Milligan",
    "D'ANDREATTA, Ana Regina",
    "DANIEL, Alberto",
    "D'ANNENCY, Frei Germano",
    "DE LA VEGA, Joaquim de Soto Garcia",
    "DENTE, Edgar Gonçalves",
    "DIAS, Francisco",
    "DIAS, Marcos de Souza",
    "DINIZ, Raimundo Nonato da Rocha",
    "DRIVER, Charles Henry",
    "DUARTE, Hélio de Queiroz",
    "DUBUGRAS, Victor",
    "DUTRA, Miguel Archanjo Benício da Assumpção",
    "EKMAN, Carlos",
    "Empresa de Trilhos Urbanos ",
    "Empresa Municipal de Urbanização ",
    "Escritório de Architetura e Construção Olavo Franco Caiuby",
    "Escritório Ramos de Azevedo",
    "Escritório Técnico H. C. Pujol Jr., Fred Reiman, Tito de Carvalho",
    "Escritório Técnico Ramos de Azevedo, Severo & Villares S. A.",
    "Escritório Técnico Samuel e Christiano das Neves",
    "Escritório Técnico Siciliano e Silva",
    "ESTEVES, José Pais",
    "FARIA, José Custódio de Sá e",
    "FARIA, Teodório Rodrigues de",
    "FAYET, Carlos Maximiliano",
    "FELDMAN, Miguel",
    "FERNANDES, Domingos",
    "FERNANDES, Lygia",
    "FERREIRA, Cristóvão José",
    "FERREIRA, João da Costa",
    "FERREIRA, José Mamede Alves",
    "FIDIÉ, Cosme Daminhão da Cunha",
    "FILHO, Silveira",
    "FILICAIA, Baccio de",
    "FONSECA, Edgar de Oliveira",
    "FONYAT FILHO, José Bina",
    "FORTE, Miguel",
    "Forte-Gandolfi",
    "FOUNDOUKAS, Marc Demetre",
    "FOURNIÉ, Victor",
    "FOX, Daniel Mackinson",
    "FRAGELLI, Marcello Accioly",
    "FRAGELLI, Marcelo Accioly",
    "FRANÇA, Luiz da Penha",
    "FRANCO, Luiz Roberto Carvalho",
    "FRANCO, Pedro Augusto Vasques",
    "FREITAS, Bernardo Casimiro de",
    "FREITAS, Ernesto Sampaio",
    "GALBINSKI, José",
    "GALVÃO, Frei Antoniode Sant'Anna",
    "GALVÃO, Raphael",
    "GANDINO, Giácomo",
    "GANDOLFI, José Maria",
    "GANDOLFI, Roberto Luiz",
    "GASPERINI, Gian Carlo",
    "GAUDENZI, José Américo",
    "GENNARO, João Eduardo de",
    "GERARD, Marcelino",
    "GIRE, Joseph ",
    "GÓIS, Pero",
    "Gomes de Almeida Fernandes",
    "GOMES, Jose Cláudio",
    "GONÇALVES, Cristóvão",
    "GONÇALVES, Oswaldo Corrêa",
    "GORDINHO, Antonieta Chaves Cintra",
    "GOROVITZ, Matheus",
    "GRINOVER, Lucio",
    "GRUNEWALD, Heitor Rademaker",
    "GUEDES SOBRINHO, Joaquim Manoel",
    "GUILBERT, Albert",
    "GUILLOBEL, Joaquim Cândido",
    "GUSMÃO, Alexandre de",
    "HANAOKA, Kazuo",
    "HAUSSLER, Matheus",
    "HEDBERG, Carl Gustav",
    "HEEP, Adolf Franz",
    "HEHL, Maximilian Emil",
    "HERMAN, Luis Felipe Aflalo",
    "Hidroservice Engenharia Ltda",
    "IERVOLINO, Affonso",
    "IMPÉRIO, Flávio",
    "INDIO DA COSTA, Luiz Eduardo",
    "Instituto de Orientação às Cooperativas Habitacionais de São Paulo (INOCOOP SP)",
    "JACOME, Irmão Diogo",
    "JÁCOME, Manuel Ferreira",
    "JANNUZZI, Luís",
    "JOBIM FILHO, Walter",
    "JORDÃO, Elias Fausto Pacheco",
    "JORGE, Wilson Edson",
    "JULIANO, Miguel",
    "JURADO, João Artacho",
    "KAMIMURA, Massayoshi",
    "KATINSKY, Julio Roberto",
    "KLIASS, Rosa Grena Alembick",
    "KOELER, Julius Friedrich",
    "KOGAN, Mauricio",
    "KORNGOLD, Lucjan",
    "KRUG, George",
    "KUHLMANN, Alberto",
    "KURY, Eduardo",
    "KUSAKA, Elza",
    "LACERDA, Antonio de",
    "LACERDA, Augusto Frederico de",
    "LANDI, Antônio José",
    "LEAL, Manuel Ferreira",
    "LEÃO, Carlos de Azevedo",
    "LEFÈVRE, José Eduardo de Assis",
    "LEFÈVRE, Rodrigo Brotero",
    "LEMOS, Carlos Alberto Cerqueira",
    "LENCI, RENATO ",
    "LEVI, Rino",
    "LIBERAL, José Soares",
    "LIBESKIND, David",
    "LIEDERS, Michael",
    "LIEUTHIER, Jean Louis",
    "LIMA, Attílio Corrêa",
    "LIMA, João da Gama Filgueiras",
    "LIMA, José Porfírio de",
    "LIMA, Richard",
    "LINZ, Cristóvão",
    "LISBOA, Antônio Francisco",
    "LISBOA, Joaquim Miguel Ribeiro",
    "LOHBAUER, Philipp",
    "LONGO, Eduardo",
    "LOPES, Humberto Lemos",
    "LOTUFO, Vitor Amaral",
    "LOTUFO, Zenon",
    "LOURENÇO, Cesar Galha Bergstrom",
    "LUÍS, Domingos",
    "MACEDO, Josué",
    "MACHADO, Lucio Gomes",
    "MAGALHÃES JR., José",
    "MAGALHÃES JÚNIOR, José",
    "MAGALHÃES, Frei Gregório de",
    "MAGALHÃES, José Francisco Xavier",
    "MAGALHÃES, José Tibúrcio Pereira de",
    "MAGALHÃES, Sérgio",
    "MAGRO, Bruno Simões",
    "MAITREJEAN, Jon Andoni Vergareche",
    "MALAGRIDA, Gabriel",
    "MANGE, Ernest Robert de Carvalho",
    "MANSO, José Patrício da Silva",
    "MARINHO, Hélio Ribas ",
    "MARIUTTI, Germano",
    "MARTIN, Jules",
    "MARTINHO, Araken",
    "MARTINO, Arnaldo Antonio",
    "MARX, Roberto Burle",
    "MATHIAS, Alfredo",
    "MATOS, Antônio Fernandes de",
    "MEDELLA, Roque Soares de",
    "MELLO, Eduardo Augusto Kneese de",
    "MELLO, Eduardo de Castro",
    "MELLO, Ícaro de Castro",
    "MELO, Frei Antonio Inácio do Coração de Jesus",
    "MESQUITA, Arthur",
    "MESQUITA, Francisco Frias da",
    "MILA, Ariosto",
    "MILLAN, Carlos Barjas",
    "MILMAN, Iso",
    "MINDLIN, Henrique Ephin",
    "MIRANDA, Bento",
    "MONTEIRO, Domingos José",
    "MONTEIRO, Francisco da Nova",
    "Monteiro, Heinsfurter & Rabinovitch",
    "MONTESANO, Dario",
    "MONTIGNY, Auguste Henri Victor Grandjean de",
    "MORAES JÚNIOR, Luiz de",
    "MORALES, Adolpho Rubio",
    "MOREIRA, Jorge Machado",
    "MORRISON, Walter Lawson",
    "MOTTA, Carlos",
    "MOURA, Luiz Eduardo Frias de",
    "MOURA, Padre José de",
    "MOURÃO, D. Luiz Antônio de Souza Botelho",
    "MOYA, Antônio Garcia",
    "MÜLLER, Daniel Pedro",
    "MUMFORD, William",
    "MURSA, Joaquim de Souza",
    "MUYLAERT, Sandra",
    "NAGAI, Satoru",
    "NAVES, Carlos Alberto",
    "NAVES, Cláudio",
    "NEDDERMEYER, José Amaral ",
    "NETTO, Antônio Augusto Antunes",
    "NETTO, Domingos Theodoro de Azevedo",
    "NETTO, Luiz Forte",
    "NETTO, Marcos Konder",
    "NETTO, Wilson Reis",
    "NEVES, Christiano Stockler das",
    "NEVES, José Maria da Silva",
    "NEVES, Júlio José Franco",
    "NIEMEYER, Oscar Ribeiro de Almeida de",
    "NIGRIELLO, Andreína",
    "NÓBREGA, Padre Manuel da",
    "NOGUEIRA, Irmão Mateus",
    "NOGUEIRA, José Mario",
    "NOGUEIRA, Mauro Luiz Neves",
    "NOZU, Sérgio",
    "NUNES, Padre Leonardo",
    "OHTAKE, Ruy Massashi",
    "OLIVEIRA, Marcial Fleury de",
    "OLIVEIRA, Sidney de ",
    "Ottoni Arquitetos Associados",
    "OTTONI, David Araujo Benedito",
    "OURIQUE, José Jacquesda Costa",
    "PACHECO, Tércio Fontana",
    "PADOVANO, Bruno Roberto",
    "PAES, Garcia Rodrigues",
    "PAIS, Fernão Dias",
    "PALANTI, Giancarlo",
    "PALLAMIN, Vera Maria",
    "PASSOS, Francisco de Oliveira",
    "PASTA, Hélio",
    "PENNA, Herculano Velloso Ferreira",
    "PENNA, Ivo de Azevedo",
    "PENTEADO, Hélio Maria",
    "PENTEADO, Hernani do Val",
    "PEREIRA, Manoel",
    "PEREIRA, Miguel Alves",
    "PEREIRA, Sérgio Ferro",
    "PERRONE, Rafael Antônio Cunha",
    "PEYRONTON, Charles",
    "PEYROTON, Charles",
    "PFISTERER, Peter",
    "PICCHI, Atílio",
    "PICCHIA, Paulo Celso del",
    "PICCOLO, Douglas et al",
    "PILON, Jacques Emile Paul",
    "PIMENTEL, Fernando Manoel de Souza Costa Freire",
    "PINOTTI, Ferrucio Julio",
    "PINTO, José de Almeida",
    "PIRES, Padre Antônio",
    "PIRES, Salvador",
    "PITOMBO, Antônio Carlos de Moraes",
    "Polizotto S/A Serralheria Artística e Ind. Ltda",
    "PORTO, Sidônio",
    "PRADO, Amador Cintra do",
    "PRADO, Diogo Benedito dos Santos",
    "PRESTES MAIA, Francisco",
    "PRETO, Manuel",
    "Projecon Soc. Civil Ltda.",
    "PUCCI, Luigi",
    "PUJOL JÚNIOR, Hippolyto Gustavo",
    "PUJOL, Hippolyto Gustavo",
    "PUTTKAMER, Hermann von",
    "RAMALHO JÚNIOR, Joel ",
    "RAMOS DE AZEVEDO, Francisco de Paula ",
    "RAMOS, Jorge Wilheim",
    "RAMOS, Waldir",
    "RANZINI, Felisberto",
    "RATH, Carlos Friedrich Josef",
    "REAL, João de Macedo Corte",
    "REBELO, José Maria Jacinto",
    "REBOUÇAS FILHO, Antônio Pereira",
    "REBOUÇAS, Diógenes de Almeida",
    "REGO, Flávio Marinho",
    "REIDY, Affonso Eduardo",
    "REINACH, Henrique de Castro",
    "REIS FILHO, Nestor Goulart",
    "REIS, Francisco de Assis Couto dos",
    "REIS, José de Souza",
    "RENDU, Auguste",
    "RÉVY, Jean",
    "RIBEIRO, Mário",
    "RIBEIRO, Paulo Antunes",
    "Rino Levi Arquitetos Associados Ltda",
    "RISI JÚNIOR, Affonso",
    "ROBERTO, Marcelo",
    "ROBERTO, Maurício",
    "ROBERTO, Milton",
    "ROBERTS, William Milnor",
    "ROCHA, Manuel dos Reis Pinto da",
    "ROCHA, Paulo Archias Mendes da",
    "RODRIGUES, Augusto",
    "RODRIGUES, Eduardo de Jesus",
    "RODRIGUES, Irmão Vicente",
    "RODRIGUES, Jayme Campello Fonseca",
    "RODRIGUES, Lúcio Martins",
    "ROLLA, Joaquim",
    "ROMIEU, Charles",
    "Rosa Grena Kliass Paisag., Planej. e Projetos Ltda",
    "ROSÁRIO, Frei Luiz do",
    "ROSSI, Cláudio",
    "ROSSI, Domiziano",
    "ROZA, Ary Garcia",
    "RUCHTI, Jacob Mauricio ",
    "RUCHTI, Karl Friedrich",
    "RUDGE, Guilherme Maxwell",
    "SABÓIA E SILVA, Domingos Sérgio",
    "SACK, Werner",
    "SAIA, Luís",
    "SALDANHA, Manuel Cardoso de",
    "SALTINI, Giulio",
    "SALVADOR, Frei Simão do",
    "SANOVICZ, Abrahão Velvu",
    "SANTA MARIA, Frei Manuel de",
    "SANTOS, Fernando César Belloube dos",
    "SANTOS, Francisco dos",
    "SÃO BENTO, Bernardo de ",
    "SÃO FRANCISCO, Frei Miguel",
    "SÃO JOÃO, Macário de ",
    "SÃO PAULO, Frei Antônio de",
    "SARAIVA, Pedro Paulo de Mello",
    "SAUER, Arthur",
    "SAWAYA, Sylvio Barros",
    "SEGNINI JUNIOR, Francisco",
    "SEVERO, Ricardo",
    "SHARP, Robert",
    "SICILIANO, Heribaldo",
    "SIEVERS, Ricardo",
    "SILVA, Luiz José Gonçalvez da",
    "SILVA, Miguel Juliano e",
    "SIMÕES, José Roberto Leme",
    "SIQUEIRA, D. Ângela de",
    "SIQUEIRA, D. Leonor de",
    "SOARES, Oscar Ribeiro de Almeida de Niemeyer",
    "SÓCRATES, Jodete Rios",
    "SOUTO, Mário Salles",
    "SOUZA, Abelardo Reidy de",
    "SOUZA, Antonio Francisco de Paula",
    "SOUZA, Jorge Félix de",
    "SOUZA, Luiz",
    "SOUZA, Mário Rodrigue de",
    "SOUZA, Martim Afonso de",
    "SOUZA, Tomé de",
    "SOUZA, Wladimir Alves de",
    "STEVAUX, Eusébio",
    "SZPIGEL, Samuel",
    "TALAAT, Alfred",
    "TAMAKI, Waldemar Teru",
    "TEBAS, Joaquim Pinto de Oliveira",
    "TEIXEIRA, Frei Mauro",
    "TELLES, Armando Carlos da Silva",
    "TELLES, Francisco Teixeira da Silva",
    "TELLES, Jayme da Silva",
    "TERESA, Manoel de Santa",
    "THIVIER, Eugéne",
    "TIBAU, Roberto J. Goulart",
    "TIBAU, Roberto José Goulart",
    "TIBIRIÇÁ, Martim Afonso",
    "TOLEDO, Aldary Henrique",
    "TOLEDO, Augusto de",
    "TOSCANO, João Walter",
    "TOSCANO, Odiléa Helena Setti",
    "TREZZA, Nelson",
    "VAL, João Gomes do",
    "VALDETARO, Oscar",
    "VALIM, Manoel de Aguilar",
    "VALORI, Giuseppe",
    "VARNHAGEN, Friedrich Ludwig Wilheim von",
    "VASCONCELLOS, Ernani Mendes de",
    "VAUTHIER, Louis Léger",
    "VELOSO, Diogo da Silveira",
    "VENCHIARUTTI, Vasco Antonio ",
    "VERJOVSKY, Olga",
    "VIANNA, Raymundo",
    "VIANNA, Rubens Carneiro",
    "VILLANOVA, Aurélio",
    "VILLAVECCHIA, Luigi",
    "VILLEGAIGNON, Nicolau Durand de",
    "VIVEIROS, José de",
    "WAEHNELDT, Carl Friedrich Gustav ",
    "Waldemar Cordeiro Paisag., Planej. e Projetos Ltda.",
    "WARCHAVCHIK, Gregori Ilych ",
    "WEISCHENCK, Guilherme Benjamin",
    "WILHEIM, Jorge",
    "WINTER, Guilherme Ernesto",
    "WYZENSKI, Cristino",
    "YURGEL, Marlene",
    "ZALSZUPIN, Jorge",
    "ZANETTINI, Siegbert ",
    "ZEIGER, Jayme",
    "ZIMBRES, Paulo de Melo",
    "ZMEKHOL, Roger",
    "ZOLKO, Gregório "
  ];

  $("#workAuthor" ).autocomplete({
    source: availableTags
  });

  $("#photo_workAuthor" ).autocomplete({
    source: availableTags
  });
});

