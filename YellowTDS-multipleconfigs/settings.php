<?php
$cloSettings =
[
//senha para a página de administração do cloaker
"adminPassword" => "12345qweasd",

//se você adicionar um domínio aqui, apenas usuários deste domínio poderão acessar a página de administração
//todos os outros receberão um 404
"adminDomain" => "",

//AVISO: se você estiver usando nginx, altere a configuração do seu site para impedir que as pessoas
//baixem seu banco de dados, ou apenas renomeie o arquivo db para que a segurança por obscuridade funcione! :-D
"dbConnection" => "clicks.db",

//se você quiser atualizar automaticamente as geobases do MaxMind
//vá para maxmind.com, registre-se, obtenha uma chave de API e coloque-a aqui
"maxMindKey" => "",

//defina como true se você quiser usar a página de agradecimento universal (UTP) em vez das páginas de agradecimento das suas landings,
//UTP se traduz automaticamente para o idioma do usuário e permite que você gerencie
//facilmente pixels para Facebook/TikTok/Google e outras fontes.
"useUTP" => false,

//se true o cloaker vai:
//- mostrar erros PHP se houver,
//- não vai ofuscar nenhum código javascript
//- adicionar rastreamento a alguns javascripts (eles vão imprimir informações no console do navegador)
//- vai adicionar cabeçalhos YWB à resposta, onde você poderá ver quanto tempo leva para processar as requisições
"debug" => true,

//diretório raiz para todos os caches
"cachingDir" => "caching",

//pasta onde todas as landings e prelandings são armazenadas (dentro de cachingDir)
"landingFolder" => "landings",

//pasta onde todas as páginas brancas são armazenadas (dentro de cachingDir)
"whiteFolder" => "whites",

//pasta para cache de recursos de página branca CURL (dentro de cachingDir, gerenciado automaticamente)
"whiteCurlCache" => "whites_curl",

//pasta para cache do DeviceDetector (dentro de cachingDir)
"devicesCache" => "devices",

//pasta para cache de taxas de câmbio (dentro de cachingDir)
"currencyCache" => "currency"
];

function get_cache_path(string $subKey): string {
    global $cloSettings;
    return $cloSettings['cachingDir'] . '/' . $cloSettings[$subKey];
}