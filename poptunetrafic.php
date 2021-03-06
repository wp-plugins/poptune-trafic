﻿<?php 

/*
Plugin Name: PopTuneTrafic - WordPress Social PopUp
Plugin Script: poptunetrafic.php
Plugin URI: http://www.trafi-ctune.com
Description: Découvrez comment vous pouvez faire apparaitre votre site web dans les comptes sociaux de plus de 1 milliard d’utilisateurs sur Twitter, Facebook, Google Plus et maintenant LinkedIn !
Version: 3.1.3
Author: Media Info Produit
Author URI: http://www.mediainfoproduit.com

--- CE PLUGIN ET TOUS LES FICHIERS INCLUS SONT COPYRIGHT Â © TRAFICTUNE 2011. 
VOUS NE POUVEZ MODIFIER, REVENDRE, DISTRIBUER OU COPIER CE CODE EN QUELQUE SORTE. ---

*/

/*-----------------------------------------------------------------------------------*/
/*	Définir quoi que ce soit nécessaire
/*-----------------------------------------------------------------------------------*/

define('STPOP_LOCATION', WP_PLUGIN_URL . '/'.basename(dirname(__FILE__)));
define('STPOP_PATH', plugin_dir_path(__FILE__));

if(file_exists(STPOP_PATH.'tc_framework.php')){
	
	include(STPOP_PATH.'tc_framework.php');
	define('STPOP_FRAMEWORK', true);
	define('STPOP_SETTINGS', "stpop_tc_settings_page");
	define('STPOP_SETTINGS_CSS', STPOP_LOCATION.'/tc_framework.css');
	define('STPOP_LOADER', 'stpop_tc_jsloader');
	define('STPOP_FUNCTION', 'PopTuneTrafic_tc');
		
} else {
	
	define('STPOP_SETTINGS', "stpop_settings_page");
	define('STPOP_SETTINGS_CSS', STPOP_LOCATION.'/settings.css');
	define('STPOP_LOADER', 'stpop_jsloader');
	define('STPOP_FUNCTION', 'PopTuneTrafic');

}

/*-----------------------------------------------------------------------------------*/
/*	Chargeur de JS 
/*-----------------------------------------------------------------------------------*/

function stpop_jsloader(){
	
	// Veillez à ce que nous ne sommes pas dans la section admin
	if (!is_admin()) {
		
		// Rincer la JS
		wp_deregister_script('facebook');
		wp_deregister_script('twitter');
		wp_deregister_script('plusone');
		wp_deregister_script('stp');

		// Inscrivez-les avec appels frais
		wp_register_script('facebook', 'http://connect.facebook.net/fr_FR/all.js#xfbml=1', false, '1.0', false);
		wp_register_script('twitter', 'http://platform.twitter.com/widgets.js', false, '1.0', false);
		wp_register_script('plusone', 'https://apis.google.com/js/plusone.js', false, '1.0', false);
		wp_register_script('stp', STPOP_LOCATION.'/stp.js', false, '3.1.0', false);

		// Incluez-les
		wp_enqueue_script('jquery');
		wp_enqueue_script('facebook');
		wp_enqueue_script('twitter');
		wp_enqueue_script('plusone');
		wp_enqueue_script('stp');
		
		// Rincer, Inscrivez, Incluez Trafic Pop CSS
		wp_deregister_style('stpCSS');
		wp_register_style('stpCSS', STPOP_LOCATION.'/poptunetrafic.css');
		wp_enqueue_style('stpCSS');
		
	}
	
}

/*-----------------------------------------------------------------------------------*/
/*	Message Propre 
/*-----------------------------------------------------------------------------------*/

function stpop_cleanMsg($input){

	$output = nl2br($input);
	$output = str_replace("\n", '', $output);
	$output = str_replace("\r", '', $output);
	return $output;	
	
}

/*-----------------------------------------------------------------------------------*/
/*	Créer des paramètres de Page
/*-----------------------------------------------------------------------------------*/

function stpop_create_menu(){
	
	// Ajoute un onglet dans le panneau options dans WordPress Admin
	$page = add_options_page("Paramètres de la PopUp PopTuneTrafic", "PopTuneTrafic™", 'administrator', __FILE__, STPOP_SETTINGS);

	//call register settings function
	add_action( 'admin_init', 'stpop_register_settings' );
	
	// Hook style sheet loading
	add_action( 'admin_print_styles-' . $page, 'stpsettings_admin_cssloader' );
	
} function stpsettings_admin_css(){
	
	/* Register our stylesheet. */
	wp_register_style( 'stpsettings', STPOP_SETTINGS_CSS );
		
} function stpsettings_admin_cssloader(){
	
       // It will be called only on your plugin admin page, enqueue our stylesheet here
       wp_enqueue_style( 'stpsettings' );
	   
} // End admin style CSS

/*-----------------------------------------------------------------------------------*/
/*	Affichager la Popup
/*-----------------------------------------------------------------------------------*/

function PopTuneTrafic(){

	// Obtenir toutes les options requises pour le popup
	$stp_title = get_option('stpop-title');
	$stp_message = esc_textarea(stpop_cleanMsg(get_option('stpop-message')));
	$stp_google_url = get_option('stpop-google-url');
	$stp_fb_url = get_option('stpop-fb-url');
	$stp_linkedin_url = get_option('stpop-linkedin-url');
	$stp_twitter_name = get_option('stpop-twitter-name');
	$stp_twitter_method = get_option('stpop-twitter-method');
	$stp_countdown = get_option('stpop-countdown');
	$stp_wait = get_option('stpop-wait');
	$stp_close = get_option('stpop-close');
	$stp_enabled = get_option('stpop-enabled');
	$stp_opacity = get_option('stpop-opacity');
	$stp_advanced = get_option('stpop-advanced');
	$stp_onclick = get_option('stpop-onclick');
		
	// Continuer uniquement si l'option pop-up est activée...
	if($stp_enabled == 'true'){ ?>
							
			<script language="javascript">
					
				jQuery(document).ready(function() {		
								
					jQuery().PopTuneTrafic({
						// Configurer l'affichage de popup
						title: "<?PHP echo $stp_title; ?>",
						message: "<?PHP echo $stp_message; ?>",
						closeable: <?PHP echo $stp_close; ?>,
						advancedClose: <?PHP echo $stp_advanced; ?>,
						opacity: '0.<?PHP echo $stp_opacity; ?>',
						// Confifgure URLs and Twitter
						google_url: "<?PHP echo $stp_google_url; ?>",
						fb_url: "<?PHP echo $stp_fb_url; ?>",
						twitter_user: "<?PHP echo $stp_twitter_name; ?>",
						twitter_method: "<?PHP echo $stp_twitter_method; ?>",
						linkedin_url: "<?PHP echo $stp_linkedin_url; ?>",
						// Set timers
						timeout: <?PHP echo $stp_countdown; ?>,
						wait: "<?PHP echo $stp_wait; ?>",
						onClick: "<?PHP echo $stp_onclick; ?>"
					});
					
				});
				
			</script>

	<?PHP
		
	} // Fin si activée
		
} // Fin de fonction principale

/*-----------------------------------------------------------------------------------*/
/*	Créer des paramètres
/*-----------------------------------------------------------------------------------*/

function stpop_register_settings() {
	
	//S'inscrire à nos réglages
	register_setting( 'stp-settings-group', 'stpop-pages');
	register_setting( 'stp-settings-group', 'stpop-page-selector');
	register_setting( 'stp-settings-group', 'stpop-enabled');
	register_setting( 'stp-settings-group', 'stpop-fb-enabled');
	register_setting( 'stp-settings-group', 'stpop-fb-layout');
	register_setting( 'stp-settings-group', 'stpop-fb-showfaces');
	register_setting( 'stp-settings-group', 'stpop-fb-colorscheme');
	register_setting( 'stp-settings-group', 'stpop-fb-api-enabled');
	register_setting( 'stp-settings-group', 'stpop-google-enabled');
	register_setting( 'stp-settings-group', 'stpop-google-annotation');
	register_setting( 'stp-settings-group', 'stpop-google-size');
	register_setting( 'stp-settings-group', 'stpop-google-api-enabled');
	register_setting( 'stp-settings-group', 'stpop-twitter-enabled');
	register_setting( 'stp-settings-group', 'stpop-twitter-api-enabled');
	register_setting( 'stp-settings-group', 'stpop-linkedin-enabled');
	register_setting( 'stp-settings-group', 'stpop-linkedin-url');
	register_setting( 'stp-settings-group', 'stpop-title' );
	register_setting( 'stp-settings-group', 'stpop-message' );
	register_setting( 'stp-settings-group', 'stpop-google-url' );
	register_setting( 'stp-settings-group', 'stpop-fb-url' );
	register_setting( 'stp-settings-group', 'stpop-twitter-name' );
	register_setting( 'stp-settings-group', 'stpop-twitter-method' );
	register_setting( 'stp-settings-group', 'stpop-twitter-url' );
	register_setting( 'stp-settings-group', 'stpop-twitter-text' );
	register_setting( 'stp-settings-group', 'stpop-twitter-count' );
	register_setting( 'stp-settings-group', 'stpop-twitter-by' );
	register_setting( 'stp-settings-group', 'stpop-close' );
	register_setting( 'stp-settings-group', 'stpop-advanced' );
	register_setting( 'stp-settings-group', 'stpop-countdown' );
	register_setting( 'stp-settings-group', 'stpop-wait' );
	register_setting( 'stp-settings-group', 'stpop-opacity' );
	register_setting( 'stp-settings-group', 'stpop-delay' );
	register_setting( 'stp-settings-group', 'stpop-fb-locale' );
	register_setting( 'stp-settings-group', 'stpop-onclick' );
	register_setting( 'stp-settings-group', 'stpop-background-color' );
	register_setting( 'stp-settings-group', 'stpop-border-color' );
	register_setting( 'stp-settings-group', 'stpop-banner-color' );
	register_setting( 'stp-settings-group', 'stpop-title-color' );
	register_setting( 'stp-settings-group', 'stpop-message-color' );
	
	// Appliquer les options par défaut aux paramètres
	add_option( 'stpop-opacity', '35' );
	add_option( 'stpop-advanced', 'false' );
	add_option( 'stpop-close', 'false' );
	add_option( 'stpop-enabled', '3' );
	add_option( 'stpop-fb-enabled', 'true' );
	add_option( 'stpop-fb-layout', 'button_count' );
	add_option( 'stpop-fb-showfaces', 'false' );
	add_option( 'stpop-fb-colorscheme', 'light' );
	add_option( 'stpop-fb-api-enabled', 'true' );
	add_option( 'stpop-google-enabled', 'true' );
	add_option( 'stpop-google-annotation', 'bubble' );
	add_option( 'stpop-google-size', 'standard' );
	add_option( 'stpop-google-api-enabled', 'true' );
	add_option( 'stpop-twitter-enabled', 'true' );
	add_option( 'stpop-twitter-api-enabled', 'true' );
	add_option( 'stpop-twitter-method', 'tweet' );
	add_option( 'stpop-twitter-count', 'horizontal' );
	add_option( 'stpop-twitter-by', 'false' );
	add_option( 'stpop-linkedin-enabled', 'true' );
	add_option( 'stpop-linkedin-url', 'http://tyler.tc/' );
	add_option( 'stpop-countdown', '25' );
	add_option( 'stpop-wait', '0' );
	add_option( 'stpop-delay', '0' );
	add_option( 'stpop-page-selector', '2' );
	add_option( 'stpop-fb-locale', 'en_US' );
	add_option( 'stpop-onclick', 'stp-open' );
	add_option( 'stpop-background-color', '#FFFFFF' );
	add_option( 'stpop-border-color', '#333333' );
	add_option( 'stpop-banner-color', '#4074CF' );
	add_option( 'stpop-title-color', '#222222' );
	add_option( 'stpop-message-color', '#FFFFFF' );

}

/*-----------------------------------------------------------------------------------*/
/*	Voir la Page des paramètres
/*-----------------------------------------------------------------------------------*/

function stpop_settings_page() {
		
    // Si l'enregistrement touche est ok:
    if( isset($_POST['saveS']) ) {
		
        // Enregistrez la valeur validée dans la base de données
		update_option('stpop-enabled', $_POST['stpop-enabled']);
		update_option('stpop-title', $_POST['stpop-title']);
		update_option('stpop-message', $_POST['stpop-message']);
		update_option('stpop-google-url', $_POST['stpop-google-url']);
		update_option('stpop-fb-url', $_POST['stpop-fb-url']);
		update_option('stpop-linkedin-url', $_POST['stpop-linkedin-url']);
		update_option('stpop-twitter-name', $_POST['stpop-twitter-name']);
		update_option('stpop-twitter-method', $_POST['stpop-twitter-method']);
		update_option('stpop-close', $_POST['stpop-close']);
		update_option('stpop-advanced', $_POST['stpop-advanced']);
		update_option('stpop-countdown', $_POST['stpop-countdown']);
		update_option('stpop-wait', $_POST['stpop-wait']);
		update_option('stpop-opacity', $_POST['stpop-opacity']);
		update_option('stpop-onclick', $_POST['stpop-onclick']);
		// Maintenant, nous pouvons afficher la page des options HTML:
?>

        <div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>

<?php } ?>

<center>
<br><br>
<script type="text/javascript"><!--
document.write('<s'+'cript type="text/javascript" src="http://www.trafic-tune.com/show.php?z=1,26,27&j=1&code='+new Date().getTime()+'"></s'+'cript>'); 
// --></script>

<script type="text/javascript"><!--
document.write('<s'+'cript type="text/javascript" src="http://www.trafic-tune.com/show.php?z=1,26,27&j=1&code='+new Date().getTime()+'"></s'+'cript>'); 
// --></script>
</center><br><br>

 
<div>
    
    <form method="post" action="options.php">
    <?php settings_fields( 'stp-settings-group' ); ?>

	<div class="tq_heading"><div>Paramètres de la PopUp PopTuneTrafic</div></div>
    
	<div class="tq_option">
    
        <label for="stpop-enabled">Activer / désactiver la PopUp</label>

			<select name="stpop-enabled" id="stpop-enabled">
                <option value="true" <?PHP if(get_option('stpop-enabled') == 'true'){echo 'selected="selected"';} ?>>Activer</option>
                <option value="false" <?PHP if(get_option('stpop-enabled') == 'false'){echo 'selected="selected"';} ?>>Désactiver</option>
			</select>

		<div class="tq_description">Activer / désactiver la PopUp Facebook.</div> 
        
    </div>

    <div class="tq_option alt">
    
        <label for="stpop-title">Titre de la popup</label>

		<input class="field" name="stpop-title" type="text" id="stpop-title" value="<?php echo get_option('stpop-title'); ?>" />
                        
        <div class="tq_description">Titre / texte de barre et titre de votre popup.</div>
        
    </div>
    
    <div class="tq_option">
    
        <label for="stpop-message">Message contextuel</label><br />

        <?PHP
		
			wp_editor( stripslashes(get_option('stpop-message')), 'stpop-message', array( 'textarea_name' => 'stpop-message', 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) );
			
		?>
        
        <br /><div class="tq_description">Le message que vous souhaitez afficher à l'intérieur de votre popup.</div>

    </div>

    <div class="tq_option alt">
    
        <label for="stpop-google-url">URL « +1 » pour Google</label>

		<input class="field" name="stpop-google-url" type="text" id="stpop-google-url" value="<?php echo get_option('stpop-google-url'); ?>" />
        
        <div class="tq_description">L'URL que vous voulez être 1'ed ou partagé sur Google.</div>
        
    </div>

    <div class="tq_option">
    
        <label for="stpop-fb-url">URL « J'aime » pour Facebook</label>

		<input class="field" name="stpop-fb-url" type="text" id="stpop-fb-url" value="<?php echo get_option('stpop-fb-url'); ?>" />
        
        <div class="tq_description">L'URL vous la quel vous voulez des « J'aime » sur Facebook lorsque vous appuyez sur le bouton J'aime.</div>
        
    </div>

    <div class="tq_option alt">
    
        <label for="stpop-fb-url">URL à partager sur LinkedIn</label>

		<input class="field" name="stpop-linkedin-url" type="text" id="stpop-linkedin-url" value="<?php echo get_option('stpop-linkedin-url'); ?>" />
        
        <div class="tq_description">Entrez l'URL que vous souhaitez utiliser avec le bouton de partage de LinkedIn.</div>
        
    </div>

    <div class="tq_option">
    
        <label for="stpop-twitter-name">Nom d'utilisateur Twitter</label>

		<input class="field" name="stpop-twitter-name" type="text" id="stpop-twitter-name" value="<?php echo get_option('stpop-twitter-name'); ?>" />
        
        <div class="tq_description">Le nom d'utilisateur Twitter à utiliser avec le bouton suivre ex : @usename</div>
        
    </div>

    <div class="tq_option alt">
    
        <label for="stpop-twitter-method">Méthode de Twitter</label>

			<select name="stpop-twitter-method" id="stpop-twitter-method">
                <option value="follow" <?PHP if(get_option('stpop-twitter-method') == 'follow'){echo 'selected="selected"';} ?>>Bouton de suivre</option>
                <option value="tweet" <?PHP if(get_option('stpop-twitter-method') == 'tweet'){echo 'selected="selected"';} ?>>Bouton de Tweet</option>
			</select>
        
        <div class="tq_description">Choisir de montrer le bouton Tweet ou le bouton suivre.</div>
        
    </div>
    
    <div class="tq_option">
    
        <label for="stpop-close">Afficher le bouton fermer</label>

			<select name="stpop-close" id="onoff">
                <option value="true" <?PHP if(get_option('stpop-close') == 'true'){echo 'selected="selected"';} ?> >Oui</option>
                <option value="false" <?PHP if(get_option('stpop-close') == 'false'){echo 'selected="selected"';} ?> >Non</option>
			</select>
        
        <div class="tq_description">Activer / désactiver le bouton fermer.</div>
        
    </div>

    <div class="tq_option alt">
    
        <label for="stpop-advanced">Proche de fonctionnalités avancées</label>

			<select name="stpop-advanced" id="onoff">
                <option value="true" <?PHP if(get_option('stpop-advanced') == 'true'){echo 'selected="selected"';} ?>>Activé</option>
                <option value="false" <?PHP if(get_option('stpop-advanced') == 'false'){echo 'selected="selected"';} ?>>Déactivé</option>
			</select>
        
        <div class="tq_description">Si activé, les utilisateurs peuvent fermer le popup en appuyant sur la touche ESC ou en cliquant à l'extérieur de la fenêtre contextuelle.</div>
        
    </div>

    <div class="tq_option">
    
        <label for="stpop-countdown">Durée du compte à rebours</label>

		<input class="field" name="stpop-countdown" type="text" id="stpop-countdown" value="<?php echo get_option('stpop-countdown'); ?>" />
        
        <div class="tq_description">La quantité de temps (en secondes) que la minuterie doit s'exécuter avant de fermer la fenêtre contextuelle.</div>
        
    </div>

    <div class="tq_option alt">
    
        <label for="stpop-wait">Attendre la minuterie</label>

		<input class="field" name="stpop-wait" type="text" id="stpop-wait" value="<?php echo get_option('stpop-wait'); ?>" />
        
        <div class="tq_description">Le nombre des menuets que STP doit attendre avant d'afficher la PopUp contextuel de nouveau.</div>
        
    </div>

    <div class="tq_option">
    
      <label for="stpop-opacity">Opacité de l'arrière-plan</label>

		<input class="field" name="stpop-opacity" type="text" id="stpop-opacity" value="<?php echo get_option('stpop-opacity'); ?>" />
        
        <div class="tq_description">Fond / page d'opacité de l'ombre. Valeur par défaut est 35</div>
        
    </div>

    <div class="tq_option alt">
    
      <label for="stpop-onclick">onClick Class</label>

		<input class="field" name="stpop-onclick" type="text" id="stpop-onclick" value="<?php echo get_option('stpop-onclick'); ?>" />
        
        <div class="tq_description">Entrer dans la classe à utiliser avec onClick. N'importe quel élément cliqué avec cette classe va ouvrir la popup.</div>
        
    </div>

    <div class="tq_option">
    
        <div class="tq_description"><a href="http://developers.facebook.com/docs/reference/plugins/like/" target="_blank">Cliquez ici pour générer des balises Open Graph.</a></div> 
		
        <div class="tq_description">Si vous souhaitez que Facebook affiche une image de titre, description et pérsonalisé une icône dans le profil du peuple vous devez appliquer un code metatags et OpenGraph vers les url que vous souhaitez utilisé. </div>
        
    </div>        

    <div class="tq_option alt right">
    
        <input type="submit" name="settingsBtn" id="settingsBtn" class="button-primary" value="<?php _e('Enregistrer les modifications') ?>" />
        
    </div>        

	</form>

</div>

<?php

}// Fin de page de Paramètres 

/*-----------------------------------------------------------------------------------*/
/*	Commence à afficher la PopUp
/*-----------------------------------------------------------------------------------*/

// Ajouter une crochet pour inclure les paramètres CSS
add_action( 'admin_init', 'stpsettings_admin_css' );
// créer un menu paramètres de plugin personnalisé
add_action( 'admin_menu', 'stpop_create_menu' );
// Exécuter le chargeur de Js
add_action( 'init', STPOP_LOADER );
// inclure les fichiers requis dans l'en-tête
add_action( 'wp_head', STPOP_FUNCTION );

?>