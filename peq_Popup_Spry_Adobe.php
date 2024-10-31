<?php
/*
Plugin Name: peq Popup Spry Adobe
Plugin URI: http://peq.110mb.com
Description: Abre uma pagina como tip um efeito da biblioteca Spry da adobe integrada no wordpress, use com moderação.

Author: Pablo Erick - pabloprogramador@gmail.com
Version: 1.0
Author URI: http://peq.110mb.com
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : pabloprogramador@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
FORMA DE USAR:
[peqPopup id="139" px="50" py="50" efeito="Blind" duracao="1500"  mouse="false"]vai que da rapa[/peqPopup]
*/

//DEFINE AQUI NOME DO PLUGIN
define( 'peqPop_PEQ_PLUGIN', 'peq_Popup_Spry_Adobe' );

///DEFININDO PASTA LOCAL
if ( ! defined( 'peqPop_PLUGIN_BASENOME' ) )
  define( 'peqPop_PLUGIN_BASENOME', plugin_basename( __FILE__ ) );

if ( ! defined( 'peqPop_PLUGIN_NOME' ) )
  define( 'peqPop_PLUGIN_NOME', trim( dirname( peqPop_PLUGIN_BASENOME ), '/' ) );

if ( ! defined( 'peqPop_PLUGIN_PASTA' ) )
  define( 'peqPop_PLUGIN_PASTA', '../wp-content/plugins/' . peqPop_PLUGIN_NOME .'/');


//CHAMADAS HOOKS
global $shortcode_tags;//comando filha da mae de achar
add_action('admin_menu', 'peqPop_meu_plugin_menu');
add_action('wp_head', 'peqPop_cabecalho', 10);
//add_filter('the_content', 'peqPop_conteudo', 1);
add_shortcode('peqPopup', 'peqPop_substituir_my_tags'); //TAGS PERSONALISADAS 

//add_filter('the_content', 'do_shortcode');
//add_filter('the_excerpt', 'do_shortcode');
//add_filter('widget_text', 'do_shortcode');
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');
add_filter( 'the_excerpt', 'shortcode_unautop');
add_filter( 'the_excerpt', 'do_shortcode');
add_filter( 'the_content', 'shortcode_unautop');
add_filter( 'the_content', 'do_shortcode');

global $id_spry;
$id_spry = 0;
//CRIACAO DE DADOS
if (get_option(peqPop_PEQ_PLUGIN.'_px') == '') add_option(peqPop_PEQ_PLUGIN.'_px', '10');
if (get_option(peqPop_PEQ_PLUGIN.'_py') == '') add_option(peqPop_PEQ_PLUGIN.'_py', '10');
if (get_option(peqPop_PEQ_PLUGIN.'_efeito') == '') add_option(peqPop_PEQ_PLUGIN.'_efeito', 'Fade');
if (get_option(peqPop_PEQ_PLUGIN.'_duracao') == '') add_option(peqPop_PEQ_PLUGIN.'_duracao', '1500');
if (get_option(peqPop_PEQ_PLUGIN.'_segueMouse') == '') add_option(peqPop_PEQ_PLUGIN.'_segueMouse', 'false');

//SALVA DADOS 
if (isset($_POST['peqPop_salvar'])) {
  update_option(peqPop_PEQ_PLUGIN.'_px', $_POST['dados1']);
  update_option(peqPop_PEQ_PLUGIN.'_py', $_POST['dados2']);
  update_option(peqPop_PEQ_PLUGIN.'_efeito', $_POST['dados3']);
  update_option(peqPop_PEQ_PLUGIN.'_duracao', $_POST['dados4']);
  update_option(peqPop_PEQ_PLUGIN.'_segueMouse', $_POST['dados5']);
  echo '<div class="updated"><p><strong>Salvo com sucesso.</strong></p></div>';
}
  
//CONSTRUINDO O MENU  ADMIN
//mudar nome
function peqPop_meu_plugin_menu() {
  add_menu_page(peqPop_PEQ_PLUGIN, peqPop_PEQ_PLUGIN, 10, peqPop_PLUGIN_NOME, "peqPop_admin_pag_1", peqPop_PLUGIN_PASTA.'menu-icon.png');
  //add_submenu_page(PLUGIN_NOME, "subPrimeiro", "subPrimeiro", 10, PLUGIN_NOME."_1", "funcao1");
}

//FUNCOES CHAMADAS PELO MENU
function peqPop_admin_pag_1() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('Sem permissão para acessar.') );
  }
 ?>
 
<div class="wrap">
<p><h2>peq Popup Spry Adobe</h2>
autor: Pablo Erick - plugins wordpress</p><br><br>
</div>
<form id="form1" name="form1" method="post" action="">
  <p><label>Posição X:<input name="dados1" type="text" id="dados1" value="<?php echo get_option(peqPop_PEQ_PLUGIN.'_px'); ?>" /></label></p>
  <p><label>Posição Y:<input name="dados2" type="text" id="dados2" value="<?php echo get_option(peqPop_PEQ_PLUGIN.'_py'); ?>" /></label></p>
  <p><label>Tipo de Efeito:<input name="dados3" type="text" id="dados3" value="<?php echo get_option(peqPop_PEQ_PLUGIN.'_efeito'); ?>" /></label>Exemplo: Fade, Blind...</p>
  <p><label>Duracao depois que tirar o mouse:<input name="dados4" type="text" id="dados4" value="<?php echo get_option(peqPop_PEQ_PLUGIN.'_duracao'); ?>" /></label></p>
  <p><label>PopUp segue o Mouse (True ou False):<input name="dados5" type="text" id="dados5" value="<?php echo get_option(peqPop_PEQ_PLUGIN.'_segueMouse'); ?>" /></label></p>
  <p><label><input type="submit" name="peqPop_salvar" id="peqPop_salvar" value="Salvar" /></label></p>
</form>
<?php
}

//FUNCAO CHAMADA NO CABECALHO DO SITE
function peqPop_cabecalho($cabecalho_texto = '') {
  $wpurl = get_bloginfo('wpurl');
  $cabecalho_texto .= '<script type="text/javascript" src="'.$wpurl.'/wp-content/plugins/'.peqPop_PLUGIN_NOME.'/tooltip/SpryTooltip.js"></script>';
  //$content .= '<script type="text/javascript" src="'. $wpurl .'/wp-content/plugins/tippy/dom_tooltip.js"></script>';
  echo $cabecalho_texto;
}

//MODIFICA O CONTEUDO
function peqPop_conteudo($conteudo_texto) {
  //$wpurl = get_bloginfo('wpurl');
  //$content .= 'teste';
  //$content .= get_option(PEQ_PLUGIN.'_valor1');
  //$content .= '<script type="text/javascript" src="'. $wpurl .'/wp-content/plugins/tippy/dom_tooltip.js"></script>';
  //$conteudo_texto .= "";
  echo $conteudo_texto;
}

//SUBSTITUI TAGS PERSONALIZADAS
function peqPop_substituir_my_tags($atts, $content = '') {
  global $id_spry;
  $id_spry++;
  //echo 'teste';
  extract(shortcode_atts(array(
  'id' => '',
        'px' => get_option(peqPop_PEQ_PLUGIN.'_px'),
  'py' => get_option(peqPop_PEQ_PLUGIN.'_py'),
  'efeito' => get_option(peqPop_PEQ_PLUGIN.'_efeito'),
  'duracao' => get_option(peqPop_PEQ_PLUGIN.'_duracao'),
  'mouse' => get_option(peqPop_PEQ_PLUGIN.'_segueMouse'),
  ), $atts));
  //return "<b>".$content."</b> - ".$width;
  if($id==""){
    $janela = '<div style="background-color:#FFFF99; padding:10px; width:300px;"><p><b>Nenhum id registrado</b></p></div>';
  }else{
    $q = new WP_Query('page_id='.$id);
    while ($q->have_posts()) : $q->the_post();
      $janela = get_the_content();
    endwhile;
  }
  return '<span id="surveyTrigger_'.$id_spry.'">'.$content.'</span>
<span id="survey_'.$id_spry.'">'.do_shortcode($janela).'</span>
<script type="text/javascript">
var surveyTooltip = new Spry.Widget.Tooltip("survey_'.$id_spry.'", "#surveyTrigger_'.$id_spry.'", {hideDelay: '.$duracao.', closeOnTooltipLeave: true, offsetX: "'.$px.'px", offsetY:"'.$py.'px", useEffect: "'.$efeito.'", followMouse: '.$mouse.'});
</script>';
}
?>