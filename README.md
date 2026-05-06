# YellowTDS - Cloaker em Português 🇧🇷

Sistema de cloaking profissional traduzido para português brasileiro, com correções e melhorias.

## 📋 Índice

- [O que é Cloaking?](#o-que-é-cloaking)
- [Funcionalidades](#funcionalidades)
- [Requisitos do Servidor](#requisitos-do-servidor)
- [Instalação com CloudPanel](#instalação-com-cloudpanel)
- [Instalação Manual](#instalação-manual)
- [Configuração Inicial](#configuração-inicial)
- [Criando uma Campanha](#criando-uma-campanha)
- [Sistema de Macros](#sistema-de-macros)
- [Filtros e Regras](#filtros-e-regras)
- [Fluxos e Etapas](#fluxos-e-etapas)
- [Páginas Brancas (White Pages)](#páginas-brancas-white-pages)
- [Testando o Cloaker](#testando-o-cloaker)
- [Boas Práticas](#boas-práticas)
- [Solução de Problemas](#solução-de-problemas)
- [API e Integrações](#api-e-integrações)

---

## O que é Cloaking?

Cloaking é uma técnica que apresenta conteúdo diferente para diferentes visitantes baseado em critérios específicos:

- **Tráfego "limpo" (Black)** → Vê a página de oferta (Money Page)
- **Tráfego "suspeito" (White)** → Vê uma página segura (White Page)

Isso protege suas ofertas de:
- Bots e scrapers
- Moderadores de plataformas de anúncios
- Concorrentes
- Visitantes de países não alvo

---

## Funcionalidades

### ✅ Recursos Principais

| Recurso | Descrição |
|---------|-----------|
| **Múltiplas Campanhas** | Gerencie várias campanhas em um único painel |
| **Filtros Avançados** | País, idioma, OS, navegador, ISP, device, IP range |
| **Fluxos Multi-Step** | Crie funis com múltiplas etapas |
| **A/B Testing** | Teste diferentes versões automaticamente |
| **Thompson Sampling** | Otimização automática de conversão |
| **Macros Dinâmicas** | Passe UTMs e parâmetros para a oferta |
| **Páginas Brancas** | Carregue local, via CURL ou redirect |
| **JS Check** | Detecte bots sem JavaScript |
| **Estatísticas** | Cliques, conversões, filtros em tempo real |
| **API** | Integração com trackers e ferramentas externas |

### ✅ Tipos de Filtro

- **País** - Permitir/bloquear países específicos
- **Idioma** - Idioma do navegador
- **Sistema Operacional** - Windows, macOS, Android, iOS, etc.
- **Navegador** - Chrome, Firefox, Safari, etc.
- **ISP** - Provedor de internet
- **Device Type** - Desktop, mobile, tablet
- **User Agent** - Strings personalizadas
- **IP Range** - Intervalos de IP específicos
- **Bot Detection** - Lista de bots conhecidos

---

## Requisitos do Servidor

### Mínimo
- **PHP:** 8.0 ou superior
- **Extensões PHP:** SQLite3, cURL, JSON, mbstring
- **Servidor:** Nginx ou Apache
- **RAM:** 512MB mínimo

### Recomendado
- **PHP:** 8.2+
- **RAM:** 1GB+
- **SSL:** Certificado HTTPS ativo
- **Domínio:** Próprio e configurado

---

## Instalação com CloudPanel

O CloudPanel é um painel de controle gratuito e leve para servidores VPS.

### Passo 1: Provisionar VPS

```bash
# Em um servidor Ubuntu 22.04 ou Debian 11+, execute:
wget https://installer.cloudpanel.io/ce/v2/install.sh -O cloudpanel_installer.sh
bash cloudpanel_installer.sh
```

### Passo 2: Acessar CloudPanel

Após instalação, acesse: `https://SEU_IP:8443`

Crie sua conta de administrador.

### Passo 3: Criar Site PHP

1. Vá em **Sites → Add Site → Create New Site**
2. Configure:
   - **Domain:** `seudominio.com`
   - **Site Type:** PHP
   - **PHP Version:** 8.2
   - **Document Root:** `/var/www/seudominio.com/htdocs`

### Passo 4: Fazer Upload dos Arquivos

**Opção A - Via SFTP:**
- Host: SEU_IP
- Port: 22
- User: criado no CloudPanel
- Password: sua senha
- Upload da pasta `YellowTDS-multipleconfigs` para `/htdocs/`

**Opção B - Via Git:**
```bash
cd /var/www/seudominio.com/htdocs
git clone https://github.com/digitalagencia27-cmd/yellowcloackerpt-br.git .
```

### Passo 5: Configurar Permissões

```bash
cd /var/www/seudominio.com/htdocs
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 db/
chmod -R 775 logs/
chmod -R 775 white/
chmod -R 775 black/
```

### Passo 6: Configurar SSL

No CloudPanel:
1. Selecione o site
2. Vá em **SSL/TLS**
3. Clique em **Let's Encrypt**
4. Ative o certificado gratuito

### Passo 7: Acessar o Painel

Acesse: `https://seudominio.com/admin/`

**Login padrão:**
- **Usuário:** `admin`
- **Senha:** `admin`

⚠️ **ALTERE A SENHA IMEDIATAMENTE!**

---

## Instalação Manual

### Em Servidor com Nginx

```nginx
# /etc/nginx/sites-available/seudominio.com
server {
    listen 80;
    server_name seudominio.com;
    root /var/www/seudominio.com/htdocs;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Em Servidor com Apache

O arquivo `.htaccess` já está incluído no projeto.

```bash
# Habilitar mod_rewrite
a2enmod rewrite
systemctl restart apache2
```

---

## Configuração Inicial

### 1. Alterar Senha do Admin

Após primeiro login:
1. Clique no ícone de usuário (canto superior direito)
2. Vá em **Configurações de Segurança**
3. Defina uma nova senha forte

### 2. Configurar URL de Traffic Back

**Configurações Globais → Traffic Back URL**

Esta é a URL para onde visitantes "brancos" (não qualificados) serão redirecionados quando não houver campanha ativa para o domínio.

Exemplos:
- Uma página segura genérica
- Um artigo de blog
- Sua página principal

### 3. Configurar Domínios

Em cada campanha, adicione os domínios que apontam para o cloaker:

```
seudominio.com
www.seudominio.com
*.seudominio.com  (wildcard para subdomínios)
```

---

## Criando uma Campanha

### Passo 1: Nova Campanha

1. Clique em **"Nova Campanha"**
2. Digite um nome (ex: "Oferta TikTok BR")
3. Clique em **Criar**

### Passo 2: Configurar Domínios

Na aba **Domínios**, adicione os domínios que receberão tráfego:

```
oferta1.com
oferta2.com
```

### Passo 3: Configurar Filtros (Black Settings)

Os filtros determinam quem vê a oferta real:

#### Filtros de Localização
```
Countries: BR, PT  (apenas Brasil e Portugal)
Languages: pt       (apenas idioma português)
```

#### Filtros de Dispositivo
```
OS: Android, iOS, Windows, macOS
Devices: mobile, desktop
```

#### Filtros de Navegador
```
Browsers: Chrome, Safari, Firefox
```

#### Filtros de ISP (Provedor)
```
ISP Filter: enabled
Allowed ISPs: Vivo, Claro, Tim, Oi
```

### Passo 4: Configurar Fluxos

Um fluxo é o caminho que o visitante percorre:

1. Clique em **"Adicionar Fluxo"**
2. Nomeie o fluxo (ex: "Principal")
3. Adicione etapas:

#### Etapa de Redirect
- **Tipo:** Redirect
- **URL:** `https://suaoferta.com?utm_source={c.utm_source}&clickid={clickid}`
- **Redirect Type:** 302 (temporário) ou 301 (permanente)

#### Etapa de Landing Page
- **Tipo:** Folder
- **Pasta:** Selecione uma pasta com HTML

### Passo 5: Configurar White Page

Na aba **White Page**, configure o que visitantes "brancos" verão:

**Opção 1: Página de Erro**
```
Action: Error
Error Codes: 404, 403, 500
```

**Opção 2: Página Local**
```
Action: Folder
Folder: white/politica-privacidade
```

**Opção 3: CURL (Copiar outro site)**
```
Action: CURL
URL: https://site-seguro.com
```

**Opção 4: Redirect**
```
Action: Redirect
URL: https://google.com
```

---

## Sistema de Macros

Macros permitem passar dados dinâmicos para suas URLs de oferta.

### Sintaxe

Use `{macro}` no valor do parâmetro na URL:

```
https://oferta.com/?utm_source={c.utm_source}&clickid={clickid}
```

### Macros Disponíveis

| Macro | Descrição | Exemplo |
|-------|-----------|---------|
| `{clickid}` | ID único do clique | `abc123def456` |
| `{userid}` | ID do usuário | `usr789` |
| `{domain}` | Domínio do cloaker | `oferta.com` |
| `{time}` | Timestamp atual | `1715012345` |
| `{ip}` | IP do visitante | `192.168.1.1` |
| `{country}` | País (código) | `BR` |
| `{lang}` | Idioma do navegador | `pt-BR` |
| `{os}` | Sistema operacional | `Android` |
| `{osver}` | Versão do OS | `14.0` |
| `{client}` | Navegador | `Chrome` |
| `{clientver}` | Versão do navegador | `120.0` |
| `{device}` | Tipo de dispositivo | `mobile` |
| `{brand}` | Marca do dispositivo | `Samsung` |
| `{model}` | Modelo do dispositivo | `Galaxy S21` |
| `{isp}` | Provedor de internet | `Vivo` |
| `{ua}` | User Agent completo | `Mozilla/5.0...` |

### Macros Customizadas (c.*)

Para parâmetros da URL de entrada, use o prefixo `c.`:

| Macro | Origem |
|-------|--------|
| `{c.utm_source}` | Parâmetro `utm_source` da URL |
| `{c.utm_medium}` | Parâmetro `utm_medium` da URL |
| `{c.utm_campaign}` | Parâmetro `utm_campaign` da URL |
| `{c.utm_content}` | Parâmetro `utm_content` da URL |
| `{c.utm_term}` | Parâmetro `utm_term` da URL |
| `{c.src}` | Parâmetro `src` da URL |
| `{c.qualquer}` | Qualquer parâmetro da URL |

### Exemplo Completo

**URL de entrada (tráfego):**
```
https://seudominio.com/?utm_source=tiktok&utm_medium=ads&utm_campaign=black_friday
```

**URL de redirect configurada:**
```
https://oferta.com/landing?src={c.utm_source}&med={c.utm_medium}&camp={c.utm_campaign}&clickid={clickid}&country={country}
```

**Resultado do redirect:**
```
https://oferta.com/landing?src=tiktok&med=ads&camp=black_friday&clickid=abc123&country=BR
```

### Macros Especiais

| Macro | Descrição |
|-------|-----------|
| `{hash:clickid}` | MD5 hash do clickid |
| `{hash:ip}` | MD5 hash do IP |
| `{random:1-100}` | Número aleatório entre 1 e 100 |

---

## Filtros e Regras

### Lógica dos Filtros

Os filtros funcionam em **AND** (E) entre categorias diferentes:

```
País = BR  AND  OS = Android  AND  Language = pt
```

Dentro da mesma categoria, funciona em **OR** (OU):

```
País = BR OU PT OU US
```

### Exemplo de Configuração

Para aceitar apenas:
- Brasileiros OU Portugueses
- Usando Android OU iOS
- Navegando em Chrome OU Safari

```
Countries: BR, PT
OS: Android, iOS  
Browsers: Chrome, Safari
```

### Filtros Avançados (Query Builder)

Para regras mais complexas, use o Query Builder:

```
(pais = BR AND idioma = pt) OR (pais = US AND idioma = en)
```

### ISP Filter

O filtro de ISP é útil para:
- Bloquear bots de datacenters
- Permitir apenas provedores residenciais
- Bloquear VPNs conhecidas

**Configuração recomendada:**
```
ISP Filter: Enabled
Mode: Allow List
Allowed ISPs: Vivo, Claro, Tim, Oi, NET, Telefonica
```

---

## Fluxos e Etapas

### O que são Fluxos?

Fluxos são caminhos que o visitante percorre após passar nos filtros. Você pode ter múltiplos fluxos para A/B testing.

### Tipos de Etapa

#### 1. Redirect
Redireciona diretamente para uma URL externa.

```
Tipo: Redirect
URL: https://oferta.com
Redirect Type: 302
```

#### 2. Folder (Landing Page Local)
Serve uma página HTML armazenada no servidor.

```
Tipo: Folder
Pasta: landing1
```

#### 3. CURL
Carrega conteúdo de outro site dinamicamente.

```
Tipo: CURL
URL: https://blog-exemplo.com/artigo
```

### A/B Testing

Configure múltiplas variantes com pesos:

```
Variante A: landing1 (peso: 50%)
Variante B: landing2 (peso: 30%)
Variante C: landing3 (peso: 20%)
```

### Thompson Sampling

Otimização automática que ajusta os pesos baseado em conversões:

```
Distribution: Thompson Sampling
Optimize for: conversions
```

O sistema automaticamente direciona mais tráfego para as variantes que convertem melhor.

---

## Páginas Brancas (White Pages)

### Importância

A White Page é o que moderadores, bots e concorrentes veem. Deve parecer legítima.

### Tipos de White Page

#### 1. Página de Erro
Parece que a página não existe:
```
Action: Error
Code: 404
```

#### 2. Página Local
Uma página HTML real no servidor:
```
Action: Folder
Folder: white/blog
```

#### 3. CURL (Mirror)
Copia conteúdo de outro site em tempo real:
```
Action: CURL
URL: https://wikipedia.org
```

⚠️ **Risco:** CURL pode ser lento e o site original pode bloquear.

#### 4. Redirect
Redireciona para outro site:
```
Action: Redirect
URL: https://google.com
```

### Domain-Specific White Pages

Configure white pages diferentes para cada domínio:

```
dominio1.com → White Page: blog tech
dominio2.com → White Page: loja genérica
```

### Boas Práticas para White Pages

- ✅ Use conteúdo relevante e indexável
- ✅ Mantenha estrutura de site real (menu, footer, links)
- ✅ Adicione política de privacidade e termos
- ✅ Use imagens e CSS reais
- ❌ Não use páginas vazias ou "em construção"
- ❌ Não redirecione para sites de concorrentes

---

## Testando o Cloaker

### Teste Básico

1. **Acesse a URL do domínio** sem parâmetros
2. **Verifique se vê a White Page** (se seus filtros não permitirem)

### Teste de Filtros

Use ferramentas para simular diferentes visitantes:

#### Teste de País
```
VPN para Brasil → Deve ver Money Page
VPN para EUA → Deve ver White Page
```

#### Teste de Device
```
Chrome DevTools → Toggle Device Toolbar
Simular iPhone → Verificar resultado
```

#### Teste de ISP
```
Verificar logs para ver qual ISP foi detectado
```

### Debug Mode

Ative o modo debug para ver informações detalhadas:

1. Vá em **Configurações Globais**
2. Ative **Debug Mode**
3. Acesse o site com `?debug=1`

### Verificar Logs

Os logs ficam em `/logs/`:
- `clicks.log` - Registros de cliques
- `errors.log` - Erros do sistema
- `macros.log` - Substituição de macros
- `trace.log` - Rastreamento detalhado

### Teste de Macros

Configure uma URL de redirect com macros:
```
https://httpbin.org/get?clickid={clickid}&country={country}&utm={c.utm_source}
```

Acesse com:
```
https://seudominio.com/?utm_source=tiktok
```

Verifique se os valores foram substituídos corretamente na resposta.

---

## Boas Práticas

### 🔒 Segurança

1. **Altere a senha padrão** imediatamente
2. **Use HTTPS** sempre
3. **Proteja a pasta /admin/** com IP whitelist se possível
4. **Mantenha backups** do banco de dados
5. **Não compartilhe** a URL do admin publicamente

### 📈 Performance

1. **Use CDN** para assets estáticos
2. **Comprima** HTML/CSS/JS
3. **Otimize imagens** das landing pages
4. **Use cache** quando possível
5. **Monitore recursos** do servidor

### 🎯 Campanhas

1. **Uma campanha por vertical/nicho**
2. **Teste antes de escalar**
3. **Monitore conversões** diariamente
4. **Ajuste filtros** conforme necessário
5. **Use Thompson Sampling** para otimização automática

### 📊 Estatísticas

1. **Verifique cliques vs conversões**
2. **Identifique padrões** de tráfego ruim
3. **Compare desempenho** entre fluxos
4. **Exporte dados** para análise externa

---

## Solução de Problemas

### Erro 500 (Internal Server Error)

**Causas comuns:**
- Permissões de arquivo incorretas
- Extensão PHP faltando
- Erro de sintaxe no código

**Solução:**
```bash
chmod -R 775 db/ logs/
chown -R www-data:www-data .
```

### White Page aparece para todos

**Causas:**
- Filtros muito restritivos
- Domínio não configurado na campanha
- JS Check falhando

**Solução:**
1. Verifique se o domínio está na campanha
2. Desative JS Check temporariamente
3. Verifique logs para ver motivo do filtro

### Macros não funcionam

**Causas:**
- Sintaxe incorreta
- Parâmetro não existe na URL

**Solução:**
1. Use `{c.nome}` para parâmetros da URL
2. Verifique se o parâmetro existe na URL de entrada
3. Ative debug para ver logs de macros

### Banco de dados corrompido

**Solução:**
```bash
# Backup
cp db/cloaker.db db/cloaker_backup.db

# Verificar integridade
sqlite3 db/cloaker.db "PRAGMA integrity_check;"

# Recriar se necessário
rm db/cloaker.db
# O sistema criará um novo banco automaticamente
```

### Lentidão no carregamento

**Causas:**
- CURL lento para white page
- Muitos arquivos na landing
- Servidor sobrecarregado

**Solução:**
1. Use white pages locais em vez de CURL
2. Otimize assets das landings
3. Aumente recursos do servidor

---

## API e Integrações

### Postback URL

Registre conversões externamente:

```
POST /api/postback.php
{
    "clickid": "abc123",
    "status": "conversion",
    "value": 100.00
}
```

### Events API

Registre eventos personalizados:

```
POST /api/events.php
{
    "clickid": "abc123",
    "event": "purchase",
    "value": 299.90
}
```

### PHP Connect

Conecte aplicações PHP externas:

```php
<?php
require_once 'api/phpconnect.php';

$api = new CloakerAPI('sua_api_key');
$click = $api->get_click('abc123');
echo $click['country'];
?>
```

### Webhook

Configure webhooks para eventos:
- Novo clique
- Conversão
- Traffic back

---

## Estrutura de Arquivos

```
YellowTDS-multipleconfigs/
├── admin/              # Painel administrativo
│   ├── index.php       # Dashboard principal
│   ├── campeditor.php  # Editor de campanhas
│   ├── statistics.php  # Estatísticas
│   └── ...
├── api/                # Endpoints de API
├── bases/              # Bases de dados (bots, devices)
├── black/              # Landing pages (money pages)
├── white/              # White pages
├── db/                 # Banco de dados SQLite
├── js/                 # JavaScript do sistema
├── logs/               # Arquivos de log
├── scripts/            # Scripts auxiliares
├── index.php           # Ponto de entrada
├── core.php            # Núcleo do sistema
├── macros.php          # Sistema de macros
└── ...
```

---

## Suporte

Para dúvidas e problemas:
1. Verifique os logs em `/logs/`
2. Ative o modo debug
3. Consulte esta documentação

---

## Licença

Este projeto é uma tradução e adaptação do YellowTDS original.

---

## Changelog

### v1.0.0 - Versão PT-BR
- Interface totalmente traduzida para português
- Correção no sistema de macros para suportar parâmetros `qs`
- Documentação completa em português
- Testado e aprovado em CloudPanel