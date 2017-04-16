<?php
/**
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'vialoja_suporte');

/** Usuário do banco de dados MySQL */
//define('DB_USER', 'suporte');
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
//define('DB_PASSWORD', 'fwqhbn2a');
define('DB_PASSWORD', '123456');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '`oZM!Gi|#OWw)Za @3M28zd+$pc7qt+`%G*)lx)8t6[*}w%}||EQZ+=/[E:$z0c|');
define('SECURE_AUTH_KEY',  'p+G-fIe5qoLz@*fgXY&j#O}c||?gR-)D?ol;;|nI|S$3hYfJ0Pq00FOheI~).GXw');
define('LOGGED_IN_KEY',    'J:&;5EH;yj &yeKx_E X+HZ?xOKFf#AH+Rqw<|%}-XlL^gMn/Anp2X&.vUAIq.b/');
define('NONCE_KEY',        '$DdKK[}dl@0Ol)f#a(^<Ioxil3prq<){j2K35C2h-Ui@7-%CsOX*D00FV+i!vR@d');
define('AUTH_SALT',        '$W8PxK4;|KoeVT7jt8kM|FTnvB>pU~#o)Z?]?Se#R__!5i<fXE+[Y9)4yKA(K?xs');
define('SECURE_AUTH_SALT', 'eS|l`Yt=l->q-fh0iB>SbXkA^peNs<J.f{G13Ui(F;;&~.)^{G2+hG+c>74hLq{>');
define('LOGGED_IN_SALT',   '|mI>wS]hxO>0kxv^ .-kVTF+7n*wpUZ-+m1B-gUS3-eN41YCtarh^h]SXsLkTFNK');
define('NONCE_SALT',       '?ol3Zcff56=5DKJj^$rTt(|p#*fh[Zx-Bg=v8VukZjgz-hnKE]+zHJ5/>+|&mL]K');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'sup_';

/**
 * O idioma localizado do WordPress é o inglês por padrão.
 *
 * Altere esta definição para localizar o WordPress. Um arquivo MO correspondente ao
 * idioma escolhido deve ser instalado em wp-content/languages. Por exemplo, instale
 * pt_BR.mo em wp-content/languages e altere WPLANG para 'pt_BR' para habilitar o suporte
 * ao português do Brasil.
 */
define('WPLANG', 'pt_BR');

/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
