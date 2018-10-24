<?php
/*
Plugin Name: Dupe Copy
Plugin URI:  https://developer.wordpress.org/plugins/otm-dupe-copy
Description: Checks all existing content to see if any of the new content matches any of the old content. This will ensure that all your articles/content is 100% unique!
Version:     1.0.0
Author:      Brody Zastrow
Author URI:  https://github.com/bzastrow232
Domain Path: /languages
*/


/**************
    GLOBAL 
***************/
$dpdupecopsettings_options = get_option('dpdupecopsettings');

/* Original Article 
*********************/
$original = $dpdupecopsettings_options['original'];

/* Spun Articles
******************/
$rewritten = $dpdupecopsettings_options['rewrite'];

/* Compare Articles
*********************/
$dp_dupecopfunction = similar_text($original, $rewritten, $result);

/* Create Admin Page 
**********************/
function dp_dupe_cop_page(){
    
    //Global Vars
    global $dpdupecopsettings_options, $result;
    
    //Start HTML
    ob_start(); 
    
    //Default Wordpress DIV ?>
    <div class = "wrap">
        <form action = "options.php" method = "POST">
            
            <?php // Set settings fields to save in this plugin, if not
                  // then the plugin will save to the wp settings 
                  // tab (Don't want that to happen) ?>
            <?php settings_fields('dpdupecopgroup'); ?>
            
            <h1> Content Uniqueness Checker Settings </h1><br />
            <h2 class = "alert-header"><strong>THE LOWER THE PERCENTAGE THE MORE UNIQUE IT IS!</strong></h2><br />
            <div class = "rows">
                <div class = "column-6">
                    <?php /*** Original Article/Content ***/ ?>
                    <div class = "form-group">
                        <h3> Paste Your Original Article/Content Here!</h3>
                        <textarea class = "form-control" name = "dpdupecopsettings[original]"><?php echo $dpdupecopsettings_options['original']; ?></textarea>
                    </div>
                </div>

                <div class = "column-6">
                    <?php /*** Rewritten Article/Content ***/ ?>
                    <div class = "form-group">
                        <h3> Paste Your New/Rewritten Article/Content Here!</h3>
                        <textarea class = "form-control" name = "dpdupecopsettings[rewrite]"><?php echo $dpdupecopsettings_options['rewrite']; ?></textarea>
                    </div>
                </div>
            </div>

            <?php /*** SUBMIT ***/ ?>
                <div class = "rows">
                    <div class = "colunm-4">
                        <input type = "submit" value = "Compare Content" class = "btn-submit" />
                    </div>
                    <div class = "colunm-6">
                        <?php if($result >= 20) { ?>
                            <div class="alert alert-danger" role="alert">
                              <?php echo $result.'% Similar'; ?>
                            </div>
                        <?php } elseif($result >= 5 && $result <= 19.99) { ?>
                            <div class="alert alert-warning" role="alert">
                              <?php echo $result.'% Similar'; ?>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-success" role="alert">
                              <?php echo $result.'% Similar'; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class = "column-2"></div>
                </div>
        </form>
    </div>

<?php 
    //Get Current Buffer Contents and delete current output buffer
    echo ob_get_clean(); 
}

/* Create Admin Tab 
**********************/
function dp_dupe_cop_tab(){

    /*
        5 Settings
        ============
        1.) Page Title
        2.) Menu Title
        3.) Menu Capability
        4.) Menu Slug
        5.) Function Name
    */
    add_options_page('dp dupecopy','Duplicate Content Checker','manage_options','dpdupecopy','dp_dupe_cop_page');
}
//Hook into admin menu 
add_action('admin_menu','dp_dupe_cop_tab');


/* Register Settings
**********************/
function dp_dupe_copy_settings(){
    
    /*
        Parameters
        ============
        1.) Option Group
        2.) Option Name
    */
    register_setting('dpdupecopgroup','dpdupecopsettings');
    
    // Initialize Bootstrap
    dp_add_scripts();
}
//Hook into admin initialize
add_action('admin_init','dp_dupe_copy_settings');


/* ADD BOOTSTRAP 
******************/
function dp_add_scripts(){
    
    //Custom CSS
    wp_register_style("custom css", plugin_dir_url( __FILE__ ) ."css/custom.css"); 
    wp_enqueue_style("custom css");
}
// Adding bootstrap to plugin
add_action('wp_enqueue_scripts','dp_add_scripts');

?>