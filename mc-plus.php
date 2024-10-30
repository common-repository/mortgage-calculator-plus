<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also inc all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           mc-plus 
 *
 * @wordpress-plugin
 * Plugin Name:       Mortgage Calculator Plus
 * Plugin URI:        https://www.mortgageratemath.com/
 * Description:       Monthly Mortgage Payment Calculator: Use the [mcplus] shortcode or the widget to display the calculator.
 * Version:           1.38
 * Author:            mcplus
 * Author URI:        https://www.mortgageratemath.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mortgage-calculator-plus
 * Domain Path:       /languages
 */
defined( 'MCPLUS_ROOT' ) or define( 'MCPLUS_ROOT', plugin_dir_path( __FILE__ ) );

class mc_plus {
	var $plugin_admin_page;
	var $settings;
	var $tab;
	var $new_extension;
	var $converted_stats;

	function __construct(){
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'admin_menu', array( $this, 'plugin_menu_link' ) );
		add_action( 'init', array( $this, 'plugin_init' ) );
	}






	function plugins_loaded(){
		load_plugin_textdomain( 'mc_plus', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	function activate(){
		if( ! get_option( 'mc_plus_settings', 0 ) ){
			$defaults = array(
				'general' => array(
					'loan_amount' => '250000',
					'downpayment_amount' => '20',
					'interest_rate_amount' => '4.6',
					'loan_term_amount' => '30',
					'tax_amount' => '1.2',
					'insurance_amount' => '800',
					'hoa_amount' => '60',
					'pmi_amount' => '0.5',
					'enable_downpayment' => 'checked',
					'enable_tax' => null,
					'enable_insurance' => null,
					'enable_hoa' => null,
					'enable_pmi' => null,
					'enable_title' => 'checked',
				)
			);
			update_option( 'mc_plus_settings', $defaults );
		}
	}
	
	function filter_plugin_actions( $links, $file ){
		$settings_link = '<a href="options-general.php?page=' . basename( __FILE__ ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
	
	function plugin_menu_link(){
		mcplus_load_scripts();
		$this->plugin_admin_page = add_submenu_page(
			'options-general.php',
			__( 'Mortgage Calculator Plus', 'mc_plus' ),
			__( 'Mortgage Calculator Plus', 'mc_plus' ),
			'manage_options',
			basename( __FILE__ ),
			array( $this, 'admin_options_page' )
		);
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'filter_plugin_actions' ), 10, 2 );
	}
	
	function plugin_init(){
		$this->settings = get_option('mc_plus_settings');
	}
	
	function plugin_admin_tabs( $current = 'general' ){
		$tabs = array(
			'general' => __('General'),
			'CSS' => __('CSS'),
		); ?>
		<h2 class="nav-tab-wrapper">
		<?php foreach( $tabs as $tab => $name ){ ?>
			<a class="nav-tab <?php echo $tab == $current ? 'nav-tab-active' : '' ?>" href="?page=<?php echo basename( __FILE__ ) ?>&amp;tab=<?php echo $tab ?>"><?php echo $name ?></a>
		<?php } ?>
		</h2><br><?php
	}

	function admin_options_page(){
		if( get_current_screen()->id != $this->plugin_admin_page ) return;
		$this->tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		if( isset( $_POST['plugin_sent'] ) ){
			if( isset( $_POST['saveoptions'] ) ){
				$this->settings[ $this->tab ] = $_POST;
				update_option( 'mc_plus_settings', $this->settings );
			}
		}
		if( isset( $_POST['reset'] ) ){
    			$path_css = plugin_dir_path(__FILE__).'css/style.css';
    			$path_default = plugin_dir_path(__FILE__).'css/defaultstyle.css';
			copy($path_default,$path_css);
		}
		if( isset( $_POST['savecss'] ) ){
			$cssdata = '';
			if(isset($_POST['cssdata'])) {
				$cssdata = stripslashes($_POST['cssdata']);
				$path_css = plugin_dir_path(__FILE__).'css/style.css';
				$path_default = plugin_dir_path(__FILE__).'css/defaultstyle.css';
				$css_fp = fopen($path_css,"w");
				fwrite($css_fp,$cssdata);
				fclose($css_fp);
			}
		}
 		?>
		<div class="wrap">
			<h2><?php _e( 'Mortgage Calculator Plus', 'mc_plus' ); ?></h2>
			<img src='https://www.mortgageratemath.com/img/sg.gif' width='1' height='1'/>
			<?php if( isset( $_POST['plugin_sent'] ) ) echo '<div class="updated"><p>' . __('Settings saved.') . '</p></div>'; ?>
			<form method="post" novalidate action="<?php echo admin_url( 'options-general.php?page=' . basename( __FILE__ ) ) ?>">
				<input type="hidden" name="plugin_sent" value="1"><?php
				$this->plugin_admin_tabs( $this->tab );
				switch( $this->tab ):
					case 'general':
						$this->tab_general();
						break;
					case 'CSS':
						$this->tab_css();
						break;
				endswitch; ?>
			</form>
		</div><?php
	}

	
	function tab_general(){
		global $wpdb;
		?>
		<h4>Use [mcplus] shortcode or the widget to display the Mortgage Calculator</h4>
		<table style="width:50%" class="form-table">
			<tr>
				<th>
					<label for="q_field_1"><?php _e( 'Default Loan Amount $', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0" max="999999999" step="1000.0" name="loan_amount" placeholder="250000" value="<?php echo $this->settings[ $this->tab ]['loan_amount'] ?>" id="q_field_1"> $
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_2"><?php _e( 'Default Downpayment %', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0" max="100" step="0.5" name="downpayment_amount" placeholder="20" value="<?php echo $this->settings[ $this->tab ]['downpayment_amount'] ?>" id="q_field_2"> %
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_3"><?php _e( 'Default Interest Rate %', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0" max="100" step="0.1" name="interest_rate_amount" placeholder="4.6" value="<?php echo $this->settings[ $this->tab ]['interest_rate_amount'] ?>" id="q_field_3"> %
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_4"><?php _e( 'Default Loan Term (Years)', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0" max="100" step="1" name="loan_term_amount" placeholder="30" value="<?php echo $this->settings[ $this->tab ]['loan_term_amount'] ?>" id="q_field_4"> 
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_15"><?php _e( 'Default Property Tax %', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0" max="100" step="0.05" name="tax_amount" placeholder="1.2" value="<?php echo $this->settings[ $this->tab ]['tax_amount'] ?>" id="q_field_15"> %
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_16"><?php _e( 'Default Home Insurance $', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0" max="10000000" step="25" name="insurance_amount" placeholder="800" value="<?php echo $this->settings[ $this->tab ]['insurance_amount'] ?>" id="q_field_16"> $
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_17"><?php _e( 'Default Annual HOA Fee $', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0" max="10000000" step="5" name="hoa_amount" placeholder="60" value="<?php echo $this->settings[ $this->tab ]['hoa_amount'] ?>" id="q_field_17"> $
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_18"><?php _e( 'Default Annual PMI %','mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="number" min="0.0" max="100.0" step=".05" name="pmi_amount" placeholder="0.5" value="<?php echo $this->settings[ $this->tab ]['pmi_amount'] ?>" id="q_field_18"> %
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_5"><?php _e( 'Enable Downpayment Field', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="checkbox" name="enable_downpayment" value="checked" id="q_field_5" <?php echo isset( $this->settings[ $this->tab ]['enable_downpayment'] ) ? $this->settings[ $this->tab ]['enable_downpayment'] : '' ?>>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_7"><?php _e( 'Enable Property Tax', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="checkbox" name="enable_tax" value="checked" id="q_field_7" <?php echo isset( $this->settings[ $this->tab ]['enable_tax'] ) ? $this->settings[ $this->tab ]['enable_tax'] : '' ?>>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_8"><?php _e( 'Enable Home Insurance', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="checkbox" name="enable_insurance" value="checked" id="q_field_8" <?php echo isset( $this->settings[ $this->tab ]['enable_insurance'] ) ? $this->settings[ $this->tab ]['enable_insurance'] : '' ?>>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_9"><?php _e( 'Enable HOA Fee', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="checkbox" name="enable_hoa" value="checked" id="q_field_9" <?php echo isset( $this->settings[ $this->tab ]['enable_hoa'] ) ? $this->settings[ $this->tab ]['enable_hoa'] : '' ?>>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_6"><?php _e( 'Enable PMI Field', 'mc_plus' ) ?></label> 
				</th>
				<td>
					<input type="checkbox" name="enable_pmi" value="checked" id="q_field_6" <?php echo isset( $this->settings[ $this->tab ]['enable_pmi'] ) ? $this->settings[ $this->tab ]['enable_pmi'] : '' ?>>
				</td>
			</tr>
			<tr>
				<th>
					<label for="q_field_55"><?php _e( 'Enable Mortgage Calculator title and footer', 'mc_plus' ) ?></label>
				</th>
				<td>
					<input type="checkbox" name="enable_title" value="checked" id="q_field_55" <?php echo isset( $this->settings[ $this->tab ]['enable_title'] ) ? $this->settings[ $this->tab ]['enable_title'] : '' ?>>
				</td>
			</tr>
			</td>
		</table>
		<table>
			<tr>
				<th>
					<label for="q_field_26">Preview:</label> 
				</th>
			</tr>
			<tr>
			<td>
		         	
			<?php	echo mcplus_draw_calc(null); ?>
			</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name='saveoptions' class="button button-primary button-large" value="<?php _e('Save') ?>"></p><?php
	}
	function tab_css(){
		global $wpdb;
    		$path_css = plugin_dir_path(__FILE__).'css/style.css';
		//error_log("path: ".$path_css."\n", 3, "/home/apache/mclog");
		$css_fp= fopen($path_css,"r");
		$csscontents = fread($css_fp,filesize($path_css));
		fclose($css_fp);
		?>
		<h4>Edit the CSS here to change the plugin styling</h4>
		<div style="inline-block">
		<textarea name="cssdata" rows="120" cols="100"><?php echo "$csscontents"; ?></textarea>
		<p class="submit"><input id="savebutton" type="submit" name='savecss' class="button button-primary button-large" value="<?php _e('Save') ?>"></p>
		<p class="submit"><input id='coffee-submit' type='submit' name = 'reset' class="button button-primary button-large" value = "<?php _e('Reset to default') ?>"></p>
		</div>
		<?php
	}


	






}
$mc_plus_var = new mc_plus();
register_activation_hook( __FILE__, array( $mc_plus_var, 'activate' ) );

function mcplus_draw_calc($atts)
{
	$settings =  get_option( 'mc_plus_settings', 0 );
	$down=$settings['general']['enable_downpayment']?1:0;
	$showtitle=$settings['general']['enable_title']?1:0;
	$tax=$settings['general']['enable_tax']?1:0;
	$insurance=$settings['general']['enable_insurance']?1:0;
	$hoa=$settings['general']['enable_hoa']?1:0;
	$pmi=$settings['general']['enable_pmi']?1:0;
	$loan_amount=$settings['general']['loan_amount'];
	$down_amount=$settings['general']['downpayment_amount'];
	$interest_rate_amount=$settings['general']['interest_rate_amount'];
	$term_amount=$settings['general']['loan_term_amount'];
	$tax_amount=$settings['general']['tax_amount'];
	$insurance_amount=$settings['general']['insurance_amount'];
	$hoa_amount=$settings['general']['hoa_amount'];
	$pmi_amount=$settings['general']['pmi_amount'];
	$bonus_height = $down*30 + $tax*30 + $insurance*30 + $hoa*30 + $pmi*30 + 220;
	$image_url  = plugin_dir_url(__FILE__).'images/mcs.png';
	$title_style= $showtitle ? "" : "display:none;";
	$img_style = $showtitle ? "visible" : "hidden";
	$showtitle_footer = $showtitle ? '<p style="font:.75em normal sans-serif;color:inherit;text-decoration:none;display:inline-block;margin:auto 8px;padding:.5em 1em;">Powered by <a href="https://www.mortgageratemath.com/monthly-mortgage-payment-calculator" style="font:1em normal sans-serif;color:inherit;text-decoration:none;">Mortgage Calculator</a></p>' : "";

$str1 = '<div class="mcplus_container" ><p style="'.$title_style.'text-align:center;margin:0;"><a style="color: #00000000;" href="https://www.mortgageratemath.com/" onclick="return false;" target="_blank" rel="noopener"><img style="visibility:'.$img_style.'" id="mcplus_img" height="22" width="183" src="'.$image_url.'" alt="https://www.mortgageratemath.com/" border="0" /></a></p>';
	$str2 = mcplus_tblMortgagePaymentSimple($down,$tax,$insurance,$hoa,$pmi,$loan_amount,$down_amount,$interest_rate_amount,$term_amount,$tax_amount,$insurance_amount,$hoa_amount,$pmi_amount);
        $output_str = $str1.'<div style="width:100%;min-height:'.$bonus_height.'px" frameborder="0">'.$str2.'</div>'.$showtitle_footer.'</div>';	
	return $output_str;
}

function mcplus_draw_calc_widget($atts)
{
	$settings =  get_option( 'mc_plus_settings', 0 );
	$down=$settings['general']['enable_downpayment']?1:0;
	$showtitle=$settings['general']['enable_title']?1:0;
	$tax=$settings['general']['enable_tax']?1:0;
	$insurance=$settings['general']['enable_insurance']?1:0;
	$hoa=$settings['general']['enable_hoa']?1:0;
	$pmi=$settings['general']['enable_pmi']?1:0;
	$loan_amount=$settings['general']['loan_amount'];
	$down_amount=$settings['general']['downpayment_amount'];
	$interest_rate_amount=$settings['general']['interest_rate_amount'];
	$term_amount=$settings['general']['loan_term_amount'];
	$tax_amount=$settings['general']['tax_amount'];
	$insurance_amount=$settings['general']['insurance_amount'];
	$hoa_amount=$settings['general']['hoa_amount'];
	$pmi_amount=$settings['general']['pmi_amount'];
	$bonus_height = $down*30 + $tax*30 + $insurance*30 + $hoa*30 + $pmi*30 + 220;
	$image_url  = plugin_dir_url(__FILE__).'images/mcs.png';
	$title_style= $showtitle ? "" : "display:none;";
	$img_style = $showtitle ? "visible" : "hidden";
	$showtitle_footer = $showtitle ? '<p style="font:.75em normal sans-serif;color:inherit;text-decoration:none;display:inline-block;margin:auto 8px;padding:.5em 1em;">Powered by <a href="https://www.mortgageratemath.com/monthly-mortgage-payment-calculator" style="font:1em normal sans-serif;color:inherit;text-decoration:none;">Mortgage Calculator</a></p>' : "";

$str1 = '<div class="mcplus_widget_container" style="width:100%;  text-align: center;"><p style="'.$title_style.'text-align:center;margin:0;"><a  style="color:#00000000;" href="https://www.mortgageratemath.com/" onclick="return false;" target="_blank" rel="noopener"><img style="visibility:'.$img_style.'" id="mcplus_widget_img" height="22" width="183"  src="'.$image_url.'" alt="https://www.mortgageratemath.com/" border="0" /></a></p>';
	$str2 = mcplus_tblMortgagePaymentSimple_widget($down,$tax,$insurance,$hoa,$pmi,$loan_amount,$down_amount,$interest_rate_amount,$term_amount,$tax_amount,$insurance_amount,$hoa_amount,$pmi_amount);
        $output_str = $str1.'<div style="width:100%;min-height:'.$bonus_height.'px" frameborder="0">'.$str2.'</div>'.$showtitle_footer.'</div>';	
	return $output_str;
}


function mcplus_load_scripts() {
    $path_css = plugin_dir_url(__FILE__).'css/style.css';
    $path_js = plugin_dir_url(__FILE__).'js/mcplus.js';
    wp_enqueue_style( 'mcplus_style', $path_css);
    wp_enqueue_script( 'mcplus_js', $path_js,array('jquery'),null,true);

    $settings =  get_option( 'mc_plus_settings', 0 );
    $showtitle=$settings['general']['enable_title']?1:0;
    if($showtitle) {
	$image_url  = plugin_dir_url(__FILE__).'images/mcs.png';
	$link_pre = '<link rel="preload" href="'.$image_url.'" as="image"/>';
	echo "$link_pre\n";
    }
}
add_shortcode('mcplus','mcplus_draw_calc');
add_action( 'wp_enqueue_scripts', 'mcplus_load_scripts' );

include ( MCPLUS_ROOT. 'inc/simple.php' );
include ( MCPLUS_ROOT. 'inc/widget.php' );
