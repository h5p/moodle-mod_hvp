<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

$string['modulename'] = 'Conteúdo interativo';
$string['modulename_help'] = 'O módulo de atividade H5P permite que você crie conteúdos interativos como Vídeos interativos, Conjuntos de questões, Questões Drag and Drop, Questões de múltipla escolha, Apresentações e muito mais.

In addition to being an authoring tool for rich content, H5P enables you to import and export H5P files for effective reuse and sharing of content.

User interactions and scores are tracked using xAPI and are available through the Moodle Gradebook.

You add interactive H5P content by creating content using the built-in authoring tool or uploading H5P files found on other H5P enabled sites.';
$string['modulename_link'] = 'https://h5p.org/moodle-more-help';
$string['modulenameplural'] = 'Conteúdo interativo';
$string['pluginadministration'] = 'H5P';
$string['pluginname'] = 'H5P';
$string['intro'] = 'Introdução';
$string['h5pfile'] = 'Arquivo H5P';
$string['fullscreen'] = 'Tela cheia';
$string['disablefullscreen'] = 'Desativar tela cheia';
$string['download'] = 'Download';
$string['copyright'] = 'Direitos de uso';
$string['embed'] = 'Embed';
$string['showadvanced'] = 'Mostrar avançado';
$string['hideadvanced'] = 'Ocultar avançado';
$string['resizescript'] = 'Inclua este script no seu website se deseja redimensionamento dinâmico no conteúdo embutido:';
$string['size'] = 'Tamanho';
$string['close'] = 'Fechar';
$string['title'] = 'Título';
$string['author'] = 'Autor';
$string['year'] = 'Ano';
$string['source'] = 'Fonte';
$string['license'] = 'Licença';
$string['thumbnail'] = 'Miniatura';
$string['nocopyright'] = 'Nenhuma informação sobre direitos autorais disponível para este conteúdo.';
$string['downloadtitle'] = 'Baixe este conteúdo como um arquivo H5P.';
$string['copyrighttitle'] = 'Ver informações de direitos autorais para este conteúdo.';
$string['embedtitle'] = 'Ver o código embutido para este conteúdo.';
$string['h5ptitle'] = 'Visite H5P.org para conferir mais conteúdo legal.';
$string['contentchanged'] = 'Este conteúdo mudou desde a última vez que você o usou.';
$string['startingover'] = "Você começará novamente.";
$string['confirmdialogheader'] = 'Confirmar ação';
$string['confirmdialogbody'] = 'Por favor confirme que deseja proseguir. Esta ação é irreversível.';
$string['cancellabel'] = 'Cancelar';
$string['confirmlabel'] = 'Confirmar';
$string['noh5ps'] = 'Não há conteúdo interativo disponível para este curso.';

$string['lookforupdates'] = 'Procurar por atualizações do H5P';
$string['updatelibraries'] = 'Atualizar todas as bibliotecas';
$string['removetmpfiles'] = 'Remover arquivos temporários antigos do H5P';
$string['removeoldlogentries'] = 'Remover entradas de log antigas do H5P';
$string['removeoldmobileauthentries'] = 'Remove old H5P mobile auth entries';

// Admin settings.
$string['displayoptiondownloadnever'] = 'Never';
$string['displayoptiondownloadalways'] = 'Always';
$string['displayoptiondownloadpermission'] = 'Only if user has permissions to export H5P';
$string['displayoptionnevershow'] = 'Nunca mostrar';
$string['displayoptionalwaysshow'] = 'Sempre mostrar';
$string['displayoptionpermissions'] = 'Mostrar apenas se o usuário possui permissões para exportar H5P';
$string['displayoptionpermissionsembed'] = 'Mostrar apenas se o usuário possui permissões para embutir H5P';
$string['displayoptionauthoron'] = 'Controlado pelo autor, padrão é habilitado';
$string['displayoptionauthoroff'] = 'Controlado pelo autor, padrão é desabilitado';
$string['displayoptions'] = 'Mostrar opções';
$string['enableframe'] = 'Mostrar a barra de ações e o quadro';
$string['enabledownload'] = 'Allow download';
$string['enableembed'] = 'Botão de embutir';
$string['enablecopyright'] = 'Botão de direitos autorais';
$string['enableabout'] = 'Botão Sobre o H5P';
$string['hubsettingsheader'] = 'Tipos de conteúdo';
$string['enablehublabel'] = 'Usar o H5P Hub';
$string['disablehubdescription'] = "É fortemente recomendado manter esta opção habilitada. O H5P Hub fornece uma interface fácil para adquirir novos tipos de conteúdo e manter conteúdos já existentes atualizados. No futuro, tornará mais fácil o compartilhamento e o reuso de conteúdo. Se esta opção for desabilitada, você terá que instalar e atualizar os conteúdos através de formulários de upload.";
$string['empty'] = 'Vazio';
$string['reveal'] = 'Mostrar';
$string['hide'] = 'Esconder';
$string['sitekey'] = 'Chave do site';
$string['sitekeydescription'] = 'A chave do site é secreta e identifica unicamente este site no Hub.';

$string['sendusagestatistics'] = 'Contribuir com estatísticas de uso';
$string['sendusagestatistics_help'] = 'As estatísticas de uso serão automaticamente reportadas para ajudar os desenvolvedores a entender melhor como o H5P é usado e para determinar o potenciais áreas de melhoria. Leia mais sobre quais <a {$a}>dados são coletados em h5p.org</a>.';
$string['enablesavecontentstate'] = 'Salvar o estado do conteúdo';
$string['enablesavecontentstate_help'] = 'Salva automaticamente o estado atual do conteúdo para cada usuário. Isso significa que o usuário pode continuar de onde parou.';
$string['contentstatefrequency'] = 'Frequência de salvamente de estado de conteúdo';
$string['contentstatefrequency_help'] = 'Em segundos, o quão frequentemente você deseja que o conteúdo do usuário seja salvo automaticamente. Aumente este número se você está tendo problema com muitas requisições ajax';
$string['enabledlrscontenttypes'] = 'Habilitar tipos de conteúdo dependentes de LRS';
$string['enabledlrscontenttypes_help'] = 'Torna possível usar tipos de conteúdo que necessitam de um Learning Record Store para funcionar adequadamente, como o conteúdo do tipo Questionário.';

// Admin menu.
$string['contenttypecacheheader'] = 'Cache dos tipos de conteúdo';
$string['settings'] = 'Configurações do H5P';
$string['libraries'] = 'Biblioteca do H5P';

// Content type cache section.
$string['ctcacheconnectionfailed'] = "Não foi possível se comunicar com o H5P Hub. Por favor, tente novamente.";
$string['ctcachenolibraries'] = 'Nenhum conteúdo foi recebido do H5P Hub. Por favor, tente novamente.';
$string['ctcachesuccess'] = 'O cache da biblioteca foi atualizado com sucesso!';
$string['ctcachelastupdatelabel'] = 'Última atualização';
$string['ctcachebuttonlabel'] = 'Atualizar cache de conteúdo';
$string['ctcacheneverupdated'] = 'Nunca';
$string['ctcachetaskname'] = 'Atualizar cache de conteúdo';
$string['ctcachedescription'] = 'Certificando-se que o cache de tipos de conteúdo está atualizado permitirá que você possa ver, baixar e utilizar as bibliotecas mais recentes. Isso é diferente de atualizar as bibliotecas proprimente ditas.';

// Upload libraries section.
$string['uploadlibraries'] = 'Enviar bibliotecas';
$string['options'] = 'Opções';
$string['onlyupdate'] = 'Somente enviar bibliotecas existentes';
$string['disablefileextensioncheck'] = 'Desabilitar verificação de arquivos de extensão';
$string['disablefileextensioncheckwarning'] = "Aviso! Desabilitar a verificação de arquivos de extensão pode resultar em implicações de segurança no upload de arquivos php. Isto, por sua vez, pode tornar possível ataques maliciosos ao seu site. Por favor, tenha certeza que sabe o que está enviando.";
$string['upload'] = 'Enviar';

// Installed libraries section.
$string['installedlibraries'] = 'Bibliotecas instaladas';
$string['invalidtoken'] = 'Token de segurança inválido.';
$string['missingparameters'] = 'Parâmetros ausentes';
$string['nocontenttype'] = 'Nenhum tipo de conteúdo foi especificado.';
$string['invalidcontenttype'] = 'O tipo de conteúdo escolhido é inválido';
$string['installdenied'] = 'Você não tem permissão para instalar tipos de conteúdo. Contate o administrador do seu site.';
$string['downloadfailed'] = 'Falha em baixar a bilbioteca solicitada.';
$string['validationfailed'] = 'O H5P solicitado não é válido';
$string['validatingh5pfailed'] = 'A validação do pacote h5p falhou.';

// H5P library list headers on admin page.
$string['librarylisttitle'] = 'Título';
$string['librarylistrestricted'] = 'Restrito';
$string['librarylistinstances'] = 'Instâncias';
$string['librarylistinstancedependencies'] = 'Dependências de instância';
$string['librarylistlibrarydependencies'] = 'Dependências de biblioteca';
$string['librarylistactions'] = 'Ações';

// H5P library page labels.
$string['addlibraries'] = 'Adicionar bibliotecas';
$string['installedlibraries'] = 'Instalar bibliotecas';
$string['notapplicable'] = 'N/A';
$string['upgradelibrarycontent'] = 'Atualizar conteúdo da biblioteca';

// Upgrade H5P content page.
$string['upgrade'] = 'Atualizar H5P';
$string['upgradeheading'] = 'Atualizar o conteúdo {$a}';
$string['upgradenoavailableupgrades'] = 'Não há nenhuma atualização disponível para este conteúdo.';
$string['enablejavascript'] = 'Por favor habilite o JavaScript.';
$string['upgrademessage'] = 'Você está prestes a atualizar a(s) instância(s) do conteúdo {$a} . Por favor selecione a versão de atualização.';
$string['upgradeinprogress'] = 'Atualizando para %ver...';
$string['upgradeerror'] = 'Um erro ocorreu enquanto parâmetros eram processados:';
$string['upgradeerrordata'] = 'Não foi possível carregar dados para a biblioteca %lib.';
$string['upgradeerrorscript'] = 'Não foi possível carregar o script de atualizações para %lib.';
$string['upgradeerrorcontent'] = 'Não foi possível atualizar o conteúdo %id:';
$string['upgradeerrorparamsbroken'] = 'Os parâmetros estão quebrados.';
$string['upgradedone'] = 'Você atualizou com sucesso a(s) instância(s) do conteúdo {$a} .';
$string['upgradereturn'] = 'Retornar';
$string['upgradenothingtodo'] = "Não há instâncias de conteúdo para atualizar.";
$string['upgradebuttonlabel'] = 'Atualizar';
$string['upgradeinvalidtoken'] = 'Erro: Token de segurança inválido!';
$string['upgradelibrarymissing'] = 'Erro: Biblioteca ausente!';
$string['upgradeerrormissinglibrary'] = 'Missing required library %lib.';
$string['upgradeerrortoohighversion'] = 'Parameters contain %used while only %supported or earlier are supported.';
$string['upgradeerrornotsupported'] = 'Parameters contain %used which is not supported.';

// Results / report page.
$string['user'] = 'Usuário';
$string['score'] = 'Pontuação';
$string['maxscore'] = 'Pontuação máxima';
$string['finished'] = 'Terminado';
$string['loadingdata'] = 'Carregando dados.';
$string['ajaxfailed'] = 'Falha ao carregar dados.';
$string['nodata'] = "Não há dados disponíveis que correspondam aos seus critérios.";
$string['currentpage'] = 'Página $current de $total';
$string['nextpage'] = 'Página seguindo';
$string['previouspage'] = 'Página anterior';
$string['search'] = 'Pesquisar';
$string['empty'] = 'Não há resultados disponíveis';
$string['viewreportlabel'] = 'Reportar';
$string['dataviewreportlabel'] = 'Ver respostas';
$string['invalidxapiresult'] = 'Nenhum resultado xAPI foi encontrado para a dada combinação de conteúdo e identificação de usuário';
$string['reportnotsupported'] = 'Não suportado';
$string['reportingscorelabel'] = 'Pontuação:';
$string['reportingscaledscorelabel'] = 'Pontuação no quadro de notas:';
$string['reportingscoredelimiter'] = 'de';
$string['reportingscaledscoredelimiter'] = ',';
$string['reportingquestionsremaininglabel'] = 'questões restantes para correção';
$string['reportsubmitgradelabel'] = 'Submeter notas';
$string['noanswersubmitted'] = 'Este usuário ainda não submeteu nenhuma resposta ao H5P';

// Editor.
$string['javascriptloading'] = 'Esperando por JavaScript...';
$string['action'] = 'Ação';
$string['upload'] = 'Enviar';
$string['create'] = 'Criar';
$string['editor'] = 'Editor';

$string['invalidlibrary'] = 'Biblioteca inválida';
$string['nosuchlibrary'] = 'Nenhuma biblioteca';
$string['noparameters'] = 'Nenhum parâmetro';
$string['invalidparameters'] = 'Parâmetros inválidos';
$string['missingcontentuserdata'] = 'Erro: Não foi possível encontrar dados do usuário de conteúdo';

$string['maximumgrade'] = 'Nota máxima';
$string['maximumgradeerror'] = 'Por favor, informe um dígito positivo válido como a pontuação máxima disponível para esta atividade';

// Capabilities.
$string['hvp:view'] = 'Veja e interaja com atividades H5P';
$string['hvp:addinstance'] = 'Criar novas atividades H5P ';
$string['hvp:manage'] = 'Editar atividades H5P existentes';
$string['hvp:getexport'] = 'Baixar arquivo .h5p quando a opção \'controlado por permissão\' estiver habilitada';
$string['hvp:getembedcode'] = 'Ver código H5P embutido quando a opção \'controlado por permissão\' estiver habilitada';
$string['hvp:saveresults'] = 'Salvar os resultados para atividades H5P concluídas';
$string['hvp:savecontentuserdata'] = 'Salvar o progresso do usuário para atividades H5P';
$string['hvp:viewresults'] = 'Visualizar os próprios resultados para atividades H5P concluídas';
$string['hvp:viewallresults'] = 'Visualizar todos os resultados par atividades H5P concluídas';
$string['hvp:restrictlibraries'] = 'Restringir acesso a certos tipos de conteúdo H5P';
$string['hvp:userestrictedlibraries'] = 'Utilizar tipos restritos de conteúdo H5P';
$string['hvp:updatelibraries'] = 'Instalar novos  conteúdos H5P ou atualizar os existentes';
$string['hvp:getcachedassets'] = 'Exigido para visualização de atividades H5P';
$string['hvp:installrecommendedh5plibraries'] = 'Instalar novo conteúdo seguro H5P recomendado pelo H5P.org';

// Capabilities error messages.
$string['nopermissiontogettranslations'] = 'You do not have permissions to retrieve translations';
$string['nopermissiontoupgrade'] = 'Você não possui permissão para atualizar bibliotecas.';
$string['nopermissiontorestrict'] = 'Você não possui permissão para acessar bibliotecas restritas.';
$string['nopermissiontosavecontentuserdata'] = 'Você não possui permissão para salvar dados de usuário de conteúdos.';
$string['nopermissiontosaveresult'] = 'Você não possui permissão para salvar resultados para este conteúdo.';
$string['nopermissiontoviewresult'] = 'Você não possui permissão para ver resultados para este conteúdo.';
$string['nopermissiontouploadfiles'] = 'Você não possui permissão para enviar arquivos aqui.';
$string['nopermissiontouploadcontent'] = 'Você não possui permissão para enviar conteúdo aqui.';
$string['nopermissiontoviewcontenttypes'] = 'Você não possui permissão para ver os tipos de conteúdo.';

// Editor translations.
$string['noziparchive'] = 'Sua versão do PHP não suporta arquivos zipados (ZipArchive).';
$string['noextension'] = 'O arquivo que você enviou não é um pacote HTML5 válido (não possui a extensão de arquivo .h5p)';
$string['nounzip'] = 'O arquivo que você enviou não é um pacote HTML5 válido (Não foi possível descompactá-lo)';
$string['noparse'] = 'Não foi possível analisar(parse) o arquivo principal h5p.json';
$string['nojson'] = 'O arquivo principal h5p.json não é válido';
$string['invalidcontentfolder'] = 'Pasta de conteúdo inválida';
$string['nocontent'] = 'Não foi possível encontrar ou analisar o arquivo content.json';
$string['librarydirectoryerror'] = 'O nome do diretório da biblioteca de ser compatível com machineName ou machineName-majorVersion.minorVersion (de library.json). (Diretório: {$a->%directoryName} , machineName: {$a->%machineName}, majorVersion: {$a->%majorVersion}, minorVersion: {$a->%minorVersion})';
$string['missingcontentfolder'] = 'Uma pasta de conteúdo válido está ausente';
$string['invalidmainjson'] = 'Um arquivo principal válido h5p.json está ausente';
$string['missinglibrary'] = 'Biblioteca exigida {$a->@library} ausente';
$string['missinguploadpermissions'] = "Note que as bibliotecas podem existir no arquivo que você enviou, mas você não tem permissão para enviar novas bibliotecas. Contate o administrador do site sobre isso.";
$string['invalidlibraryname'] = 'Nome de biblioteca inválido: {$a->%name}';
$string['missinglibraryjson'] = 'Não foi possível encontrar o arquivo library.json com um formato json válido para a biblioteca {$a->%name}';
$string['invalidsemanticsjson'] = 'Arquivo inválido semantics.json foi incluído na biblioteca {$a->%name}';
$string['invalidlanguagefile'] = 'Arquivo de linguagem inválido {$a->%file} na biblioteca {$a->%library}';
$string['invalidlanguagefile2'] = 'Arquivo de linguagem inválido {$a->%languageFile} foi incluído na biblioteca {$a->%name}';
$string['missinglibraryfile'] = 'O arquivo "{$a->%file}" está ausente da biblioteca: "{$a->%name}"';
$string['missingcoreversion'] = 'O sistema foi incapaz de instalar o componente <em>{$a->%component}</em> do pacote, requer uma versão mais recente do plugin H5P. Este site está atualmente rodando a versão {$a->%current}, e a versão exigida é {$a->%required} ou superior. Você deve considerar atualizar e tentar novamente.';
$string['invalidlibrarydataboolean'] = 'Dados inválidos fornecidos para {$a->%property} em {$a->%library}. Boolean esperado.';
$string['invalidlibrarydata'] = 'Dados inválidos fornecidos para {$a->%property} em {$a->%library}';
$string['invalidlibraryproperty'] = 'Não foi possível ler a propriedade {$a->%property} em {$a->%library}';
$string['missinglibraryproperty'] = 'A propriedade exigida {$a->%property} está ausente de {$a->%library}';
$string['invalidlibraryoption'] = 'Opção ilegal {$a->%option} em {$a->%library}';
$string['addedandupdatedss'] = 'Foi adicionada {$a->%new} nova bilioteca H5P  e {$a->%old} antiga foi atualizada.';
$string['addedandupdatedsp'] = 'Foi adicionada {$a->%new} nova bilioteca H5P  e {$a->%old} antigas foram atualizadas.';
$string['addedandupdatedps'] = 'Foram adicionadas {$a->%new} novas biliotecas H5P  e {$a->%old} antiga foi atualizada.';
$string['addedandupdatedpp'] = 'Foram adicionadas {$a->%new} novas biliotecas H5P  e {$a->%old} antigas foram atualizadas.';
$string['addednewlibrary'] = 'Foi adicionada {$a->%new} nova bilioteca H5P.';
$string['addednewlibraries'] = 'Foram adicionadas {$a->%new} new H5P libraries.';
$string['updatedlibrary'] = 'Foi atualizada {$a->%old} biblioteca H5P.';
$string['updatedlibraries'] = 'Foram atualizadas {$a->%old} bibliotecas H5P.';
$string['missingdependency'] = 'Dependência {$a->@dep} ausente exigida por {$a->@lib}.';
$string['invalidstring'] = 'A string fornecida não é válida em semântica de acordo com o regexp. (valor: \"{$a->%value}\", regexp: \"{$a->%regexp}\")';
$string['invalidfile'] = 'Arquivo "{$a->%filename}" não permitido. Apenas arquivos com as seguintes extensões são permitidos: {$a->%files-allowed}.';
$string['invalidmultiselectoption'] = 'Opção de seleção inválida em multi-seleção.';
$string['invalidselectoption'] = 'Opção de seleção inválida em seleção.';
$string['invalidsemanticstype'] = 'Erro interno H5P: unknown content type "{$a->@type}" in semantics. Removing content!';
$string['unabletocreatedir'] = 'Incapaz de criar diretório.';
$string['unabletogetfieldtype'] = 'Incapaz de capturar o tipo do campo.';
$string['filetypenotallowed'] = 'Tipo do arquivo não permitido.';
$string['invalidfieldtype'] = 'Tipo do campo inválido.';
$string['invalidimageformat'] = 'Formato de arquivo de imagem inválido. Use jpg, png ou gif.';
$string['filenotimage'] = 'O arquivo não é uma imagem.';
$string['invalidaudioformat'] = 'Formato de arquivo de áudio inválido. Use mp3 ou wav.';
$string['invalidvideoformat'] = 'Formato de arquivo de vídeo inválido. Use mp4 ou webm.';
$string['couldnotsave'] = 'Não foi possível salvar o arquivo.';
$string['couldnotcopy'] = 'Não foi possível copiar o arquivo.';
$string['librarynotselected'] = 'Você precisa selecionar o tipo de conteúdo.';

// Welcome messages.
$string['welcomeheader'] = 'Bem-vindo ao mundo do H5P!';
$string['welcomegettingstarted'] = 'Para começar a usar o H5P e Moodle dê uma olhada no nosso <a {$a->moodle_tutorial}>tutorial</a> e confira o <a {$a->example_content}>conteúdo exemplo</a> em H5P.org para inspiração.';
$string['welcomecommunity'] = 'Esperamos que você desfrute do H5P e participe de nossa crescente comunidade através dos nossos <a {$a->forums}>fórums</a>.';
$string['welcomecontactus'] = 'Se você tiver qualquer comentário, não hesite em entrar em <a {$a}>contato</a> conosco. Levamos os feedbacks bastante a sério e nos dedicamos para fazer o H5P mehor a cada dia!';
$string['missingmbstring'] = 'A extenção mbstring PHP  não foi carregada. H5P precisa desta extensão para funcionar corretamente';
$string['wrongversion'] = 'A versão da biblioteca H5P {$a->%machineName} usada neste conteúdo não é válida. O conteúdo contém {$a->%contentLibrary}, mas deveria conter {$a->%semanticsLibrary}.';
$string['invalidlibrarynamed'] = 'A biblioteca H5P {$a->%library} usada neste conteúdo não é válida.';

// Setup errors.
$string['oldphpversion'] = 'Sua versão do PHP está desatualizada. H5P requer a versão 5.2 para funcionar corretamente. Versão 5.6 ou superior são recomendadas.';
$string['maxuploadsizetoosmall'] = 'O seu tamanho de upload máximo do PHP é bem pequeno. Com a sua configuração atual, você não poderá enviar arquivos maiores que {$a->%number} MB. Isso pode causar problemas ao tentar enviar H5Ps, imagens e vídeos. Por favor, considere aumentar para mais de 5MB.';
$string['maxpostsizetoosmall'] = 'O seu tamanho de publicação máximo do PHP é bem pequeno. Com a sua configuração atual, você não poderá enviar arquivos maiores que {$a->%number} MB. Isso pode causar problemas ao tentar enviar H5Ps, imagens e vídeos. Por favor, considere aumentar para mais de 5MB.';
$string['sslnotenabled'] = 'Seu servidor não possui SSL habilitado. SSL deve ser habilitado para certificar uma conexão segura com o H5P hub.';
$string['hubcommunicationdisabled'] = 'A comunicação com o H5P hub foi desabilitada porque um ou mais requisitos H5P falharam.';
$string['reviseserversetupandretry'] = 'Quando as configurações do server forem revistas, você poderá reativar a comunicação com o H5P hub nas configurações do H5P.';
$string['disablehubconfirmationmsg'] = 'Ainda deseja habilitar o hub ?';
$string['nowriteaccess'] = 'Um problema com o acesso de escrita do servidor foi detectado. Por favor, tenha certeza que o seu servidor pode escrever em sua pasta de dados.';
$string['uploadsizelargerthanpostsize'] = 'O seu tamanho de upload máximo PHP é maior que o seu tamanho de publicação máximo. Isso é conhecido por causar alguns problemas em algumas instalações.';
$string['sitecouldnotberegistered'] = 'O site não pôde ser registrado pelo hub. Por favor, contate o administrador do seu site.';
$string['hubisdisableduploadlibraries'] = 'O H5P Hub foi desabilitado até que este problema seja resolvido. Você ainda pode enviar bibliotecas através da página "Bibliotecas H5P".';
$string['successfullyregisteredwithhub'] = 'Seu site foi registrado com sucesso pelo H5P Hub.';
$string['sitekeyregistered'] = 'Uma chave única foi fornecida a você. Esta chave identifica você com o Hub quando novas atualizações são recebidas. Esta chave está disponível para visualização na página "Configurações H5P".';

// Ajax messages.
$string['hubisdisabled'] = 'O hub está desabilitado. Você pode habilitá-lo nas configurações do H5P.';
$string['invalidh5ppost'] = 'Não foi possível postar H5P.';
$string['filenotfoundonserver'] = 'Arquivo não encontrado no servidor. Verifique as configurações de envio de arquivo.';
$string['failedtodownloadh5p'] = 'Falha ao baixar o H5P requisitado.';
$string['postmessagerequired'] = 'Uma mensagem postada é necessária para acessar o endpoint fornecido';

// Licensing.
$string['copyrightinfo'] = 'Informaçõoes de direitos autorais';
$string['years'] = 'Ano(s)';
$string['undisclosed'] = 'Não revelado';
$string['attribution'] = 'Atribuição 4.0';
$string['attributionsa'] = 'Atribuição-CompartilhaIgual 4.0';
$string['attributionnd'] = 'Atribuição-SemDerivações 4.0';
$string['attributionnc'] = 'Atribuição-NãoComercial 4.0';
$string['attributionncsa'] = 'Atribuição-NãoComercial-CompartilhaIgual 4.0';
$string['attributionncnd'] = 'Atribuição-NãoComercial-SemDerivações 4.0';
$string['gpl'] = 'Licença geral pública (GPL) v3';
$string['pd'] = 'Domínio público';
$string['pddl'] = 'Dedicação e licença de domínio público';
$string['pdm'] = 'Marca de domínio público';
$string['copyrightstring'] = 'Direitos autorais';
$string['by'] = 'por';
$string['showmore'] = 'Mostrar mais';
$string['showless'] = 'Mostrar menos';
$string['sublevel'] = 'Subnível';
$string['noversionattribution'] = 'Atribuição';
$string['noversionattributionsa'] = 'Atribuição-CompartilhaIgual';
$string['noversionattributionnd'] = 'Atribuição-SemDerivações';
$string['noversionattributionnc'] = 'Atribuição-NãoComercial';
$string['noversionattributionncsa'] = 'Atribuição-NãoComercial-CompartilhaIgual';
$string['noversionattributionncnd'] = 'Atribuição-NãoComercial-SemDerivações';
$string['licenseCC40'] = '4.0 International';
$string['licenseCC30'] = '3.0 Unported';
$string['licenseCC25'] = '2.5 Generic';
$string['licenseCC20'] = '2.0 Generic';
$string['licenseCC10'] = '1.0 Generic';
$string['licenseGPL'] = 'Licença geral pública (GPL)';
$string['licenseV3'] = 'Versão 3';
$string['licenseV2'] = 'Versão 2';
$string['licenseV1'] = 'Versão 1';
$string['licenseCC010'] = 'CC0 1.0 Universal (CC0 1.0) Dedicação de domínio público';
$string['licenseCC010U'] = 'CC0 1.0 Universal';
$string['licenseversion'] = 'Versão de licença';
$string['creativecommons'] = 'Creative Commons';
$string['ccattribution'] = 'Atribuição';
$string['ccattributionsa'] = 'Atribuição-CompartilhaIgual';
$string['ccattributionnd'] = 'Atribuição-SemDerivações';
$string['ccattributionnc'] = 'Atribuição-NãoComercial';
$string['ccattributionncsa'] = 'Atribuição-NãoComercial-CompartilhaIgual';
$string['ccattributionncnd'] = 'Atribuição-NãoComercial-SemDerivações';
$string['ccpdd'] = 'Dedicação de domínio público';
$string['yearsfrom'] = 'Anos(de)';
$string['yearsto'] = 'Anos (até)';
$string['authorname'] = "Nome do Autor";
$string['authorrole'] = "Papel do Autor";
$string['editor'] = 'Editor';
$string['licensee'] = 'Licenciado';
$string['originator'] = 'Criador';
$string['additionallicenseinfo'] = 'Qualquer informação adicional sobre a licença';
$string['licenseextras'] = 'Extras da licença';
$string['changelog'] = 'Alterar Log';
$string['question'] = 'Questão';
$string['date'] = 'Data';
$string['changedby'] = 'Alterado por';
$string['changedescription'] = 'Descrição da alteração';
$string['changeplaceholder'] = 'Foto cortada, texto alterado, etc.';
$string['additionalinfo'] = 'Informações adicionais';
$string['authorcomments'] = 'Comentários do autor';
$string['authorcommentsdescription'] = 'Comentários para o editor de conteúdo (Este texto não será publicado como parte das informações sobre direitos autorais)';

// Embed.
$string['embedloginfailed'] = 'Você não possui acesso a este conteúdo. Tente fazer login.';

// Privacy.
$string['privacy:metadata:core_files'] = 'A atividade H5P armazena arquivos que tenham sido enviados como parte do conteúdo H5P.';
$string['privacy:metadata:core_grades'] = 'A atividade H5P armazena notas do usuários que responderam o conteúdo H5P.';

$string['privacy:metadata:hvp_content_user_data'] = 'Descreve o estado atual de um conteúdo exibido para o usuário. Usado para restaurar estados antigos de conteúdo.';
$string['privacy:metadata:hvp_content_user_data:id'] = 'A ID da relção de dados de usuário e conteúdo.';
$string['privacy:metadata:hvp_content_user_data:user_id'] = 'A ID do usuário a quem os dados pertencem.';
$string['privacy:metadata:hvp_content_user_data:hvp_id'] = 'A ID do conteúdo H5P que os dados pertencem.';
$string['privacy:metadata:hvp_content_user_data:sub_content_id'] = 'Sub-conteúdo do H5P, 0 se não é sub-conteúdo.';
$string['privacy:metadata:hvp_content_user_data:data_id'] = 'Identificar de tipo de dados.';
$string['privacy:metadata:hvp_content_user_data:data'] = 'Dados armazenados do usuário.';
$string['privacy:metadata:hvp_content_user_data:preloaded'] = 'Flag que determina se dados devem ser pré-carregados no conteúdo.';
$string['privacy:metadata:hvp_content_user_data:delete_on_content_change'] = 'Flag que determina se dados devem ser deletados quando o conteúdo é alterado.';

$string['privacy:metadata:hvp_events'] = 'Mantém o controle de eventos registrados do H5P.';
$string['privacy:metadata:hvp_events:id'] = 'A ID única do evento.';
$string['privacy:metadata:hvp_events:user_id'] = 'A ID do usuário que executou a ação.';
$string['privacy:metadata:hvp_events:created_at'] = 'A hora em que o evento foi criado.';
$string['privacy:metadata:hvp_events:type'] = 'O tipo de evento.';
$string['privacy:metadata:hvp_events:sub_type'] = 'O sub-tipo de evento, ou ação do evento.';
$string['privacy:metadata:hvp_events:content_id'] = 'A ID do conteúdo em que a ação foi executada, 0 se nenhum ou novo conteúdo.';
$string['privacy:metadata:hvp_events:content_title'] = 'Título do conteúdo.';
$string['privacy:metadata:hvp_events:library_name'] = 'A biblioteca afetada pelo evento.';
$string['privacy:metadata:hvp_events:library_version'] = 'A versão da biblioteca afeitada pelo evento.';

$string['privacy:metadata:hvp_xapi_results'] = 'Armazena eventos xAPI em conteúdo H5P.';
$string['privacy:metadata:hvp_xapi_results:id'] = 'A ID única do evento xAPI.';
$string['privacy:metadata:hvp_xapi_results:content_id'] = 'A ID do conteúdo em que o evento foi executado.';
$string['privacy:metadata:hvp_xapi_results:user_id'] = 'A ID do usuário que executou a ação.';
$string['privacy:metadata:hvp_xapi_results:parent_id'] = 'A ID do conteúdo-pai do conteúdo em que o evento foi executado. Null se não há conteúdo-pai.';
$string['privacy:metadata:hvp_xapi_results:interaction_type'] = 'O tipo de interação.';
$string['privacy:metadata:hvp_xapi_results:description'] = 'A descrição, tarefa ou questão do conteúdo em que a ação foi executada.';
$string['privacy:metadata:hvp_xapi_results:correct_responses_pattern'] = 'O padrão correto de resposta.';
$string['privacy:metadata:hvp_xapi_results:response'] = 'A resposta que o usuário enviou.';
$string['privacy:metadata:hvp_xapi_results:additionals'] = 'Informações adicionais que o H5P pode enviar.';
$string['privacy:metadata:hvp_xapi_results:raw_score'] = 'Pontuação atingida para o evento.';
$string['privacy:metadata:hvp_xapi_results:max_score'] = 'Pontuação máxima adquirível para o evento.';

// Reuse.
$string['reuse'] = 'Reuse';
$string['reuseContent'] = 'Reuse Content';
$string['reuseDescription'] = 'Reuse this content.';
$string['contentCopied'] = 'Content is copied to the clipboard';