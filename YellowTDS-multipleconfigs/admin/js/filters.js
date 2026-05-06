
var tdsFilters = [
    {
        id: 'os',
        label: 'OS',
        input: 'text',
        type: 'string',
        operators: ['in', 'not_in'],
        placeholder: 'Android,iOS,Windows,OS X',
        size: 50
    },
    {
        id: 'osver',
        label: 'Versão do SO',
        input: 'number',
        type: 'integer',
        operators: ['in', 'not_in','less_or_equal','greater_or_equal'],
        placeholder: 10,
        size: 50
    },
    {
        id: 'device',
        label: 'Dispositivo',
        input: 'text',
        type: 'string',
        operators: ['in', 'not_in'],
        placeholder: 'desktop,mobile',
        size: 70
    },
    {
        id: 'brand',
        label: 'Marca',
        input: 'text',
        type: 'string',
        operators: ['contains','not_contains','in', 'not_in'],
        size: 70
    },
    {
        id: 'model',
        label: 'Modelo',
        input: 'text',
        type: 'string',
        operators: ['contains','not_contains','in', 'not_in'],
        size: 70
    },
    {
        id: 'client',
        label: 'Cliente',
        input: 'text',
        type: 'string',
        operators: ['contains', 'not_contains','in', 'not_in'],
        size: 70
    },
    {
        id: 'clientver',
        label: 'Versão Cliente',
        input: 'text',
        type: 'string',
        operators: ['less_or_equal','greater_or_equal','in', 'not_in'],
        size: 50
    },
    {
        id: 'country',
        label: 'País',
        input: 'text',
        type: 'string',
        operators: ['in', 'not_in'],
        placeholder: 'RU,BY,UA'

    },
    {
        id: 'lang',
        label: 'Idioma',
        input: 'text',
        type: 'string',
        operators: ['in', 'not_in'],
        placeholder: 'en,ru'
    },
    {
        id: 'useragent',
        label: 'User Agent',
        input: 'text',
        type: 'string',
        operators: ['contains', 'not_contains'],
        size: 70,
        placeholder: 'facebook,facebot,curl,gce-spider,yandex.com,odklbot'
    },
    {
        id: 'isp',
        label: 'ISP',
        input: 'text',
        type: 'string',
        operators: ['contains', 'not_contains'],
        size: 70,
        placeholder: 'facebook,google,yandex,amazon,azure,digitalocean,microsoft'
    },
    {
        id: 'referer',
        label: 'Referenciador',
        input: 'text',
        type: 'string',
        operators: ['equal', 'not_equal', 'contains', 'not_contains'],
        validation: {
            allow_empty_value: true
        },
        size: 70
    },
    {
        id: 'domain',
        label: 'Domínio',
        input: 'text',
        type: 'string',
        operators: ['in', 'not_in'],
        size: 70
    },
    {
        id: 'host',
        label: 'Host',
        input: 'text',
        type: 'string',
        operators: ['in', 'not_in'],
        size: 70
    },
    {
        id: 'vpntor',
        label: 'VPN&Tor',
        type: 'integer',
        input: 'radio',
        values: {
            0: 'Detectado',
            1: 'Não Detectado'
        },
        operators: ['equal']
    },
    {
        id: 'ipbase',
        label: 'Base de IP',
        type: 'string',
        operators: ['in', 'not_in'],
        placeholder: 'caminho para arquivo(s) de base na pasta bases: bots1.txt,bots2.txt',
        size: 70
    },
    {
        id: 'urlparam',
        label: 'Parâmetro URL',
        type: 'string',
        input: 'text',                
        placeholder: ['Nome do parâmetro URL', 'valor(es) separado(s) por vírgula'],
        operators: [
            'param_in',
            'param_not_in'
        ],
        size: 30
    }
];

var paramOperators = [
  {
    type: 'param_in',
    nb_inputs: 2,
    multiple: false,
    apply_to: ['string'],
    label: 'em'
  },
  {
    type: 'param_not_in',
    nb_inputs: 2,
    multiple: false,
    apply_to: ['string'],
    label: 'não em'
  }
];