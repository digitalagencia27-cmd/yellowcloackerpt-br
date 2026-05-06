[Versão em inglês](README.en.md)

```
                            Yellow TDS
    _            __     __  _ _             __          __  _
   | |           \ \   / / | | |            \ \        / / | |
   | |__  _   _   \ \_/ /__| | | _____      _\ \  /\  / /__| |__
   | '_ \| | | |   \   / _ \ | |/ _ \ \ /\ / /\ \/  \/ / _ \ '_ \
   | |_) | |_| |    | |  __/ | | (_) \ V  V /  \  /\  /  __/ |_) |
   |_.__/ \__, |    |_|\___|_|_|\___/ \_/\_/    \/  \/ \___|_.__/
           __/ |
          |___/             https://yellowweb.top

Se você gostou deste script, POR FAVOR DOE!
USDT TRC20: TKeNEVndhPSKXuYmpEwF4fVtWUvfCnWmra
Bitcoin: bc1qqv99jasckntqnk0pkjnrjtpwu0yurm0qd0gnqv
Ethereum: 0xBC118D3FDE78eE393A154C29A4545c575506ad6B
```

# Yellow TDS - Documentação (PT-BR)

## O que é

Yellow TDS - Aplicação PHP para roteamento de tráfego por regras de campanha: `white`/`black`/`trafficback`, com registro de cliques, leads, postbacks e UI para gerenciamento.

## Requisitos atuais

- PHP `>= 8.2`
- Extensões PHP: `curl`, `sqlite3`
- HTTPS funcionando no domínio
- Permissão de escrita para a pasta do projeto (criação/atualização de `db`, `logs`, `cache`, sessões)

Verificações executadas em `debug.php` a cada requisição.

## Início rápido

1. Implante o conteúdo da pasta `fromfolder/` no servidor.
2. Abra `settings.php` e no mínimo configure:
- `adminPassword`
- `adminDomain` (opcional)
- `dbConnection` (nome do arquivo SQLite em `db/`)
- `debug` (`false` para produção)
- `maxMindKey` (se precisar de atualização automática do GeoLite2)
3. Abra `https://seu-dominio/admin/` e faça login.
4. Crie uma campanha, preencha domínios, white/black, filtros, postbacks, salve.

## Fluxo principal

- `index.php` -> `tds.php` -> escolha de ação (`white`, `black`, `trafficback`)
- `core.php` coleta parâmetros do clique: IP, GEO, ISP, OS, browser, UA, query params
- `next.php` e `send.php` processam transições por etapas do funil e envio de formulários
- `postback.php` recebe status S2S e atualiza leads

## Onde os dados são armazenados

SQLite em `db/<dbConnection>`.

Tabelas principais:
- `campaigns` - campanhas e suas configurações JSON
- `clicks` - cliques permitidos e leads
- `blocked` - cliques filtrados
- `trafficback` - cliques sem campanha adequada
- `common` - configurações gerais da UI

Schema: `db/db.sql`.

## Admin

Páginas principais:
- `admin/index.php` - lista de campanhas
- `admin/campsettings.php` - configurações da campanha
- `admin/clicks.php` - allowed/blocked/leads/trafficback
- `admin/statistics.php` - tabelas agregadas

Blocos chave de configuração da campanha:
- Domínios
- Página segura (white): `folder`, `redirect`, `curl`, `error`
- Página de dinheiro (black): fluxos multi-etapas (`steps[]`, folder/redirect em cada etapa)
- Filtros (query-builder, grupos AND/OR)
- Scripts (backfix, substituir trânsito/landing, imagens lazy)
- Estatísticas (fuso horário, tabelas/colunas/agrupamento)
- Postbacks (entrada + saída S2S)
- API key para `phpconnect.php`

## Integrações

### JS connect

Conexão do script:

```html
<script src="https://seu-dominio/js/index.php"></script>
```

`js/index.php` pode:
- retornar JS com substituição de conteúdo
- mostrar iframe
- entregar meta-redirect
- processar cenário JS-check

### PHP API

Endpoint: `phpconnect.php`

Restrições:
- apenas `POST`
- `User-Agent` deve conter `YellowTDS`
- corpo: JSON com `api_key` e parâmetros (`tds_ua`, `tds_ref`, `tds_ip`, ...)

Exemplo de cliente em `phpclient.php`.

### Postback

Endpoint: `postback.php`

Parâmetros obrigatórios:
- `clickid`
- `status`
- `payout`

Opcionalmente:
- `currency` (padrão `USD`, há conversão em `currency.php`)

## UTP (Página Universal de Agradecimento)

Se `settings.php -> useUTP = true`, após o lead é usado `thankyou/index.php`.

UTP:
- escolhe/gera template
- traduz texto através de `thankyou/translator.php`
- cacheia páginas em `thankyou/cache/`
- substitui macros (`{NAME}`, `{PHONE}`, `{CLICKID}`)

## Logs e manutenção

Logs são escritos em `logs/<subdir>/`.

Frequentemente usados:
- `logs/error`
- `logs/login`
- `logs/postback`
- `logs/trace` (quando `debug=true`)

Atualização GeoLite2: `bases/update.php` (usa `maxMindKey`).

## Importante sobre segurança

- Mude imediatamente o `adminPassword`.
- Limite o `adminDomain`, se o admin deve abrir apenas de um domínio.
- Mantenha `debug=false` em produção.
- Não armazene o repositório com chaves/senhas ativas em público.
- Se usar nginx, bloqueie acesso direto ao arquivo SQLite.

## Notas sobre a versão atual

- Fonte de verdade para campanhas - UI + SQLite, não `settings.php` manual de campanhas.
- Atualizador automático em `admin/autoupdate.php` parcialmente preparado (cópia de arquivos atualmente desabilitada TODO).
- Em alguns configs dev no repositório há pastas de teste (`black`, `white`, `student`) e elas não são obrigatórias para produção.