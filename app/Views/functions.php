<?php

/**
 * WP Bootstrap 4 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Bootstrap_4
 */
remove_action('wp_head', 'wp_generator');

add_action('init', 'myStartSession', 1);
function myStartSession()
{
  if (!session_id()) {
    session_start();
  }
}

if (!function_exists('wp_bootstrap_4_setup')) :
  function wp_bootstrap_4_setup()
  {

    // Make theme available for translation.
    load_theme_textdomain('wp-bootstrap-4', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(150, 150);
    // add_image_size( 'singlepost-thumb', 9999, 9999 );


    // Enable Post formats
    add_theme_support('post-formats', array('gallery', 'video', 'audio', 'status', 'quote', 'link'));

    // Enable support for woocommerce.
    add_theme_support('woocommerce');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
      'menu-1' => esc_html__('Primary', 'wp-bootstrap-4'),
    ));

    // Switch default core markup for search form, comment form, and comments
    add_theme_support('html5', array(
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ));

    // Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('wp_bootstrap_4_custom_background_args', array(
      'default-color' => 'f8f9fa',
      'default-image' => '',
    )));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo.
    add_theme_support('custom-logo', array(
      'height'      => 250,
      'width'       => 250,
      'flex-width'  => true,
      'flex-height' => true,
    ));
  }
endif;
add_action('after_setup_theme', 'wp_bootstrap_4_setup');




/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wp_bootstrap_4_content_width()
{
  $GLOBALS['content_width'] = apply_filters('wp_bootstrap_4_content_width', 800);
}
add_action('after_setup_theme', 'wp_bootstrap_4_content_width', 0);




/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wp_bootstrap_4_widgets_init()
{
  register_sidebar(array(
    'name'          => esc_html__('Sidebar', 'wp-bootstrap-4'),
    'id'            => 'sidebar-1',
    'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-4'),
    'before_widget' => '<section id="%1$s" class="widget border-bottom %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h5 class="widget-title h6">',
    'after_title'   => '</h5>',
  ));

  register_sidebar(array(
    'name'          => esc_html__('Footer Column 1', 'wp-bootstrap-4'),
    'id'            => 'footer-1',
    'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-4'),
    'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h5 class="widget-title h6">',
    'after_title'   => '</h5>',
  ));

  register_sidebar(array(
    'name'          => esc_html__('Footer Column 2', 'wp-bootstrap-4'),
    'id'            => 'footer-2',
    'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-4'),
    'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h5 class="widget-title h6">',
    'after_title'   => '</h5>',
  ));

  register_sidebar(array(
    'name'          => esc_html__('Footer Column 3', 'wp-bootstrap-4'),
    'id'            => 'footer-3',
    'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-4'),
    'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h5 class="widget-title h6">',
    'after_title'   => '</h5>',
  ));

  register_sidebar(array(
    'name'          => esc_html__('Footer Column 4', 'wp-bootstrap-4'),
    'id'            => 'footer-4',
    'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-4'),
    'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h5 class="widget-title h6">',
    'after_title'   => '</h5>',
  ));
}
add_action('widgets_init', 'wp_bootstrap_4_widgets_init');




/**
 * Enqueue scripts and styles.
 */
function wp_bootstrap_4_scripts()
{

  wp_enqueue_style('font-awsome', 'https://use.fontawesome.com/releases/v5.8.2/css/all.css');
  wp_enqueue_style('bootstrap-4', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), 'v4.0.0', 'all');
  // wp_enqueue_style( 'beh', get_template_directory_uri() . '/assets/css/beh.css', array(), 'v1.0.0', 'all' );
  wp_enqueue_style('ico-page', get_template_directory_uri() . '/assets/css/icofont.css', array(), 'v1.0.0', 'all');
  // wp_enqueue_style( 'new-icici', get_template_directory_uri() . '/assets/css/icici-new-design.css', array(), 'v1.0.0', 'all' );
  wp_enqueue_style('MDB', get_template_directory_uri() . '/assets/css/mdb.min.css');
  wp_enqueue_style('wp-bootstrap-4-style', get_stylesheet_uri(), array(), '1.0.2', 'all');
  wp_enqueue_style('blog', get_template_directory_uri() . '/assets/css/blog.css', array(), 'v1.0.2', 'all');
  wp_enqueue_script('mdb-js', 'https://resources.freemi.in/mdbtheme/4.8.10/js/mdb.min.js');
  wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.js', array('jquery'), 'v1.0.0', true);
  wp_enqueue_script('bootstrap-4-js', get_template_directory_uri() . '/assets/js/bootstrap.js', array('jquery'), 'v4.0.0', true);
  wp_enqueue_script('wow', get_template_directory_uri() . '/assets/js/wow.js', array('jquery'), 'v1.0.0', true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'wp_bootstrap_4_scripts');


/**
 * Registers an editor stylesheet for the theme.
 */
function wp_bootstrap_4_add_editor_styles()
{
  add_editor_style('editor-style.css');
}
add_action('admin_init', 'wp_bootstrap_4_add_editor_styles');


// Implement the Custom Header feature.
require get_template_directory() . '/inc/custom-header.php';

// Implement the Custom Comment feature.
require get_template_directory() . '/inc/custom-comment.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Functions which enhance the theme by hooking into WordPress.
require get_template_directory() . '/inc/template-functions.php';

// Custom Navbar
require get_template_directory() . '/inc/custom-navbar.php';

// Customizer additions.
require get_template_directory() . '/inc/tgmpa/tgmpa-init.php';

// Use Kirki for customizer API
require get_template_directory() . '/inc/theme-options/add-settings.php';



// Customizer additions.
require get_template_directory() . '/inc/customizer.php';

// require the_breadcrumb()         . '/inc/custom-function.php';

// Load Jetpack compatibility file.
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}

// Load WooCommerce compatibility file.
if (class_exists('WooCommerce')) {
  require get_template_directory() . '/inc/woocommerce.php';
}

// cf7
function custom_filter_wpcf7_is_tel($result, $tel)
{
  $result = preg_match('/[6-9]{1}[0-9]{9}$/', $tel);
  return $result;
}
add_filter('wpcf7_is_tel', 'custom_filter_wpcf7_is_tel', 10, 2);
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Generate breadcrumbs
 * @author CodexWorld
 * @authorURL www.codexworld.com
 */
function get_breadcrumb()
{
  echo '<a href="' . home_url() . '" rel="nofollow">Home</a>';
  if (is_category() || is_single()) {
    echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
    the_category(' &bull; ');
    if (is_single()) {
      echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
      the_title();
    }
  } elseif (is_page()) {
    echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
    echo the_title();
  } elseif (is_search()) {
    echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
    echo '"<em>';
    echo the_search_query();
    echo '</em>"';
  }
}
//Load More Page

add_action('wp_ajax_load_posts_by_ajax', 'load_posts_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'load_posts_by_ajax_callback');


function load_posts_by_ajax_callback()
{
  check_ajax_referer('load_more_posts', 'security');
  $paged = $_POST['page'];
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => '3',
    'paged' => $paged,
    'orderby' => 'date',
  );
  $my_posts = new WP_Query($args);
  if ($my_posts->have_posts()) :
?> <?php while ($my_posts->have_posts()) : $my_posts->the_post(); ?> <div class="blog-size mb-4 mt-md-0 mt-5 px-2"> <a href="<?php the_permalink(); ?>">
          <div class="card border shadow" style="height:345px;">
            <div class="col-12 p-0"> <?php
                                      $feat_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                                      //echo $feat_image;
                                      if ($feat_image == '') {
                                      ?> <img class="card-img-top side-post" fetchpriority="low" src="https://resources.freemi.in/wordpress/wp-content/uploads/2020/03/Halloween-Masquerade-Ball-2.webp" alt="FreEMI blog featured image"> <?php } else { ?> <img class="card-img-top side-post" src="<?php echo $feat_image; ?>" alt="FreEMI blog featured image"> <?php } ?> </div>
            <div class="card-body col-12 pb-0">
              <h6 class="card-text text-dark  font-weight-bold mb-0"><?php the_title(); ?></h6>
              <p class="m-0"> <span class="f-11">Posted on: <?php echo get_the_date('Y-m-d'); ?></span> </p>
            </div>
            <div class="card-body col-12 pb-0"> <a href="<?php the_permalink(); ?>" class="btn-read px-2 py-1 btn">Read More</a> <?php
                                                                                                                                  $category_detail = get_the_category($post->ID); //$post->ID
                                                                                                                                  foreach ($category_detail as $cd) {
                                                                                                                                  ?> <small><span class="badge badge-info"><?php echo $cd->cat_name; ?></span></small> <?php
                                                                                                                                                                                                                      }
                                                                                                                                                                                                                        ?> </div>
          </div>
        </a> </div> <?php endwhile; ?> <?php
                                      endif;

                                      wp_die();
                                    }
                                    /**
                                     * Return only the first category when outputting the previous/next post links
                                     */
                                    function my_custom_post_navigation($terms, $object_ids, $taxonomies, $args)
                                    {

                                      return array_slice($terms, 0, 1);
                                    }

                                    /*----------------

FOR PERSONAL LOAN
------------------*/

                                    add_action('admin_post_add_personal', 'prefix_admin_add_personal');
                                    add_action('admin_post_nopriv_add_personal', 'prefix_admin_add_personal');
                                    function prefix_admin_add_personal()
                                    {
                                      $f_name             = $_POST['f_name'];
                                      $l_name             = $_POST['l_name'];
                                      $mobile             = $_POST['mobile'];
                                      $email              = $_POST['email'];
                                      $dob                = $_POST['dob'];
                                      $employmentType     = $_POST['employmentType'];
                                      $netmonthlyIncome   = $_POST['netmonthlyIncome'];
                                      $annualTurnover     = $_POST['annualTurnover'];
                                      $requiredLoanAmount = $_POST['requiredLoanAmount'];
                                      $location           = $_POST['location'];
                                      $companyName        = $_POST['companyName'];
                                      $userPAN            = strtoupper($_POST['userPAN']);
                                      $monthlyObligation  = $_POST['monthlyObligation'];
                                      $bankrelation       = $_POST['bankrelation'];
                                      $userPincode        = $_POST['userPincode'];
                                      $userState          = $_POST['userState'];
                                      $url                = $_POST['requestingPage'];
                                      $userIp             = $_POST['userIp'];
                                      $userBrowser        =  $_POST['userBrowser'];
                                      $time               = $_POST['lead_date'];
                                      $requestCategory    = $_POST['requestCategory'];
                                      $agreePolicy        =  $_POST['agreePolicy'];
                                      $token              = $_POST['uniqid'];
                                      $utmSource      =  $_POST['utmSource'];
                                      $utmCampaign      =  $_POST['utmCampaign'];
                                      $utmMedium      =  $_POST['utmMedium'];
                                      // $curl_response			=	$_POST ['agreePolicy'];


                                      $rel = $url;
                                      $uri =  explode(".in", $rel);
                                      $url = $uri['1'];
                                      $array = array(
                                        "employmenttype" => $employmentType,
                                        "companyname" => $companyName,
                                        "monthlyincome" => $netmonthlyIncome,
                                        "requiredloanamount" => $requiredLoanAmount,
                                        "annualturnover" => $annualTurnover,
                                        "mobile" => $mobile,
                                        "email" => $email,
                                        "fullName" => $f_name . " " . $l_name,
                                        "fname" => $f_name,
                                        "mname" => "",
                                        "lname" => $l_name,
                                        "password" => "",
                                        "customerID" => "",
                                        "registrationref" => "LEAD_DATA",
                                        "mobileimei" => "",
                                        "address1" => "",
                                        "address2" => "",
                                        "address3" => "",
                                        "location" => $location,
                                        "state" => $userState,
                                        "country" => "IN",
                                        "dndagree" => $agreePolicy,
                                        "systemip" => $userIp,
                                        "browserclient" => $userBrowser,
                                        "requestingpage" => $url,
                                        "agreepolicy" => $agreePolicy,
                                        "dob" => $dob,
                                        "userpincode" => $userPincode,
                                        "userpan" => $userPAN,
                                        "monthlyobligation" => $monthlyObligation,
                                        "eligiblebank" => "OTHER",
                                        "comments" => "OTHER",
                                        "leadcapturedearlier" => "N",
                                        "bankrelation" => $bankrelation,
                                        "requestcategory" => $requestCategory,
                                        "leadtype" => $requestCategory,
                                        "mobileidvalid" => "N",
                                        "emailidvalid" => "N",
                                        "panvalid" => "N",
                                        "faircenttype" => "N"
                                      );

                                      $data_string = json_encode($array);
                                      //$data_http = http_build_query($array);
                                      // echo "data in JSON <br> <br>";
                                      // echo $data_string;
                                      $mydb = new wpdb('bn_wordpress', '62945cc1f5', 'bitnami_wordpress_custom', 'localhost:3306');
                                      $rows = $mydb->get_results("SELECT * FROM api WHERE `category` = '$requestCategory'");
                                      foreach ($rows as $obj) {
                                        // echo "<br>Categoty: ".$obj->category."<br>";
                                        $curlUrl = $obj->url;
                                        // echo $curlUrl;

                                        //Create cURL connection
                                        $curl = curl_init();
                                        $api_key = "Authorization: " . $obj->token;

                                        $header_js = array('Content-Type: application/json', $api_key);
                                        // print_r ($header_js);
                                        //set cURL options
                                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                        curl_setopt($curl, CURLOPT_URL, $curlUrl);
                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($curl, CURLOPT_POST, 1);
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header_js);
                                        curl_setopt($curl, CURLOPT_VERBOSE, 1);
                                        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

                                        //Execute cURL
                                        $curl_response = curl_exec($curl);
                                        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                        $ssl = curl_getinfo($curl, CURLINFO_SSL_ENGINES);
                                        // curl info test
                                        $curl_ind = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
                                        $curl_ifo = curl_getinfo($curl);
                                        $curl_ifo = json_encode($curl_ifo, JSON_PRETTY_PRINT);

                                        // echo "<br><br><br>";
                                        //
                                        //  // echo print_r(curl_getinfo($curl));
                                        //  //Output server response
                                        //  echo $curl_ifo ;
                                        //
                                        // echo "<br> --- <br> Curl Total Time Details:  <b>" .$curl_ind. "</b>";
                                        //
                                        //
                                        // if($httpCode == 200)
                                        // 						{
                                        //
                                        // echo "<br><br><br>HTTP CODE IS :   <strong>" . $httpCode. "</strong>";
                                        // echo "<br>Response --->" . $curl_response;  //Output server response
                                        // 						}
                                        // else {
                                        // 	echo "<br><br> <br>Check Your code";
                                        // echo "<br>HTTP CODE IS:<strong>" . $httpCode. "</strong>";
                                        // echo $curl_response; //Output server response
                                        // }
                                        //Close cURL connection
                                        curl_close($curl);


                                        // DATA SAVE IN DATABASE
                                        if (isset($_POST['data'])) {
                                          sleep(5);
                                          /*
		 $host         = "localhost";
		 $dbusername   = "root";
		 $dbpassword   =   "";
		 $dbname       = "test";
		 */
                                          //----FOR SERVER ------//
                                          $host         = "localhost:3306";
                                          $dbusername   = "bn_wordpress";
                                          $dbpassword   = "62945cc1f5";
                                          $dbname       = "bitnami_wordpress_custom";
                                          ///*****------END------//
                                          //creat connection
                                          $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                          if (mysqli_connect_error()) {
                                            // die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                            echo "Sorry! Internal Exception. Try again Later.";
                                          } else {

                                            $errors = array();
                                            $query = "SELECT * FROM wp_personal_loan WHERE sessionToken='$token' LIMIT 1";
                                            $result = mysqli_query($conn, $query);
                                            $tokenS = mysqli_fetch_assoc($result);
                                            if ($tokenS) { // if user exists
                                              if ($tokenS['sessionToken'] === $token) {
                                                array_push($errors, "Token already exists");
                                              }
                                            }


                                            if (count($errors) == 0) {
                                              $sql = "INSERT INTO wp_personal_loan (first_name , last_name , email , mobile , dob , employmentType , netmonthlyIncome ,
					 requiredLoanAmount , location ,
					 companyName , annualTurnover ,
					 monthlyObligation , bankrelation, userPincode , state, userPAN , requestingPage , userIp , userBrowser, lead_date , requestCategory
					 , agreePolicy, api_response ,httpCode , sessionToken,  utm_source , utm_camp, utm_medium , address1)
				 VALUES ('$f_name' , '$l_name' , '$email' , '$mobile' , '$dob' , '$employmentType' ,  '$netmonthlyIncome' , '$requiredLoanAmount' , '$location' ,
					 '$companyName' , '$annualTurnover' , '$monthlyObligation' , '$bankrelation', '$userPincode' , '$userState' ,'$userPAN' , '$url' , '$userIp' , '$userBrowser',
						'$time' , '$requestCategory' , '$agreePolicy', '$data_string' , '$httpCode' , '$token' , 	'$utmSource' , 	'$utmCampaign' ,'$utmMedium','$location')";
                                              if ($conn->query($sql)) {
                                                echo '<script>window.location.href = "https://www.freemi.in/loans/request-success?STATUS=Y";</script>';
                                              } else {
                                                echo "<br>Error: " . $sql . "<br>" . $conn->error;
                                                echo '<div class="alert alert-danger mt-0 mb-3">Internal Exception. Please Try Again Later.</div>';
                                              }
                                              $conn->close();
                                            } else {
                                              //  echo $_POST ['uniqid'];
                                              // echo "Token Exists";
                                              echo '<script>window.location.href = "https://www.freemi.in/loans/request-success?STATUS=Y";</script>';
                                            }
                                          }
                                        }
                                      }
                                    }


                                    /*----------------

FOR PERSONAL LOAN BAJAJ
------------------*/

                                    add_action('admin_post_add_personal_bajaj', 'prefix_admin_add_personal_bajaj');
                                    add_action('admin_post_nopriv_add_personal_bajaj', 'prefix_admin_add_personal_bajaj');
                                    function prefix_admin_add_personal_bajaj()
                                    {
                                      $f_name             = $_POST['f_name'];
                                      $l_name             = $_POST['l_name'];
                                      $mobile             = $_POST['mobile'];
                                      $email              = $_POST['email'];
                                      $dob                = $_POST['dob'];
                                      $employmentType     = $_POST['employmentType'];
                                      $netmonthlyIncome   = $_POST['netmonthlyIncome'];
                                      $annualTurnover     = $_POST['annualTurnover'];
                                      $requiredLoanAmount = $_POST['requiredLoanAmount'];
                                      $location           = $_POST['location'];
                                      $address1           = "";
                                      $address2           = "";
                                      $area               = "";
                                      $companyName        = $_POST['companyName'];
                                      $userPAN            = strtoupper($_POST['userPAN']);
                                      // $cibil							=	"";
                                      $monthlyObligation  = $_POST['monthlyObligation'];
                                      $bankrelation       = $_POST['bankrelation'];
                                      $userPincode        = $_POST['userPincode'];
                                      $userState          = $_POST['userState'];
                                      $url                = $_POST['requestingPage'];
                                      $userIp             = $_POST['userIp'];
                                      $userBrowser        =  $_POST['userBrowser'];
                                      $time               = $_POST['lead_date'];
                                      $requestCategory    = $_POST['requestCategory'];
                                      $agreePolicy        =  $_POST['agreePolicy'];
                                      $token              = $_POST['uniqid'];
                                      $utmSource      =  $_POST['utmSource'];
                                      $utmCampaign      =  $_POST['utmCampaign'];
                                      $utmMedium      =  $_POST['utmMedium'];

                                      // For DB & JsonSerializable

                                      $dob_db = $_POST['dob'];
                                      $date = date_create($dob);
                                      $dob = date_format($date, "m/d/Y");
                                      $rel = $url;
                                      $uri =  explode(".in", $rel);
                                      $url = $uri['1'];
                                      // For DB & JsonSerializable
                                      if ($employmentType == "Salaried") {
                                        $income = $netmonthlyIncome;
                                      } elseif ($employmentType == "Self-Employed") {
                                        $income = $annualTurnover;
                                      } else {
                                        $income = $netmonthlyIncome;
                                      }

                                      $customer_id  = rand(100000, 999999);

                                      $array_yes_bank = array(                   //  FOR YES BANK
                                        "name" => $f_name . " " . $l_name,
                                        "emailId" => $email,
                                        "customerId" => $customer_id,
                                        "reqOriginator" => "Alternate Banking Channel",
                                        "triggerId" => rand(1000000, 9999999),
                                        "branchCode" => "555555",
                                        "enquiryType" => "AUTO LOAN",
                                        "enquiryAmount" => $requiredLoanAmount,
                                        "alertGenerationDateTime" => date("d-m-Y")
                                      );
                                      $data_string_yes_bank = json_encode($array_yes_bank);


                                      $arraySession = array(                         // for session
                                        "FirstName" => $f_name,
                                        "LastName" => $l_name,
                                        "Employment_Type" => $employmentType,
                                        "Employer_Name" => $companyName,
                                        "Monthly_Salary" => $income,
                                        "Loan"            => $requiredLoanAmount,
                                        "Monthly_Obligation" => $monthlyObligation,

                                      );

                                      $arrayLoginApi = array(                      //for loginapi
                                        "fullName" => $f_name . " " . $l_name,
                                        "fname" => $f_name,
                                        "mname" => "",
                                        "lname" => $l_name,
                                        "password" => "",
                                        "email" => $email,
                                        "mobile" => $mobile,
                                        "customerID" => "",
                                        "registrationref" => "LEAD_DATA",
                                        "requestingip" => $userIp,
                                        "clientbrowserdetails" => $userBrowser,
                                        "pan" => $userPAN,
                                        "mobileimei" => "",
                                        "address1" => "",
                                        "address2" => "",
                                        "address3" => "",
                                        "city" => $location,
                                        "state" => $userState,
                                        "country" => "IN",
                                        "pincode" => $userPincode,
                                        "leadtype" => $requestCategory,
                                        "mobileidvalid" => "N",
                                        "emailidvalid" => "N",
                                        "panvalid" => "N",
                                        "faircenttype" => "N"
                                      );
                                      $data_string_login_api = json_encode($arrayLoginApi);

                                      // YES BANK API INTRIGATION
                                      $yes_bank_fetch = new wpdb('bn_wordpress', '62945cc1f5', 'bitnami_wordpress_custom', 'localhost:3306');   //Server
                                      $yesAPI = $yes_bank_fetch->get_results("SELECT * FROM api WHERE `category` = 'YES BANK' AND `enable` = 'Y'");
                                      foreach ($yesAPI as $yes) {
                                        $yes_api_URL = $yes->url;
                                        $yes_client_secret = $yes->token;
                                        $yes_client_id = $yes->userId;
                                        $api_category = $yes->category;
                                      }
                                      $curl_yes = curl_init();
                                      $client_id = "X-IBM-Client-Id: " . $yes_client_id;
                                      $client_secret = "X-IBM-Client-Secret: " . $yes_client_secret;
                                      $pemFile = dirname(__FILE__);

                                      $file_path = $pemFile . "/ssl/";
                                      $header_yes_api = array('Content-Type: application/json', $client_id, $client_secret);
                                      // print_r ($header_yes_api);
                                      //set cURL options
                                      curl_setopt($curl_yes, CURLOPT_CUSTOMREQUEST, "POST");
                                      curl_setopt($curl_yes, CURLOPT_URL, $yes_api_URL);
                                      curl_setopt($curl_yes, CURLOPT_RETURNTRANSFER, true);
                                      curl_setopt($curl_yes, CURLOPT_POST, 1);
                                      curl_setopt($curl_yes, CURLOPT_SSL_VERIFYHOST, 2);
                                      curl_setopt($curl_yes, CURLOPT_SSL_VERIFYPEER, true);
                                      curl_setopt($curl_yes, CURLOPT_CAINFO, $file_path . "cacert.pem");
                                      curl_setopt($curl_yes, CURLOPT_CERTINFO, 1);
                                      curl_setopt($curl_yes, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                                      curl_setopt($curl_yes, CURLOPT_POSTFIELDS, $data_string_yes_bank);
                                      curl_setopt($curl_yes, CURLOPT_HTTPHEADER, $header_yes_api);
                                      curl_setopt($curl_yes, CURLOPT_VERBOSE, 1);
                                      curl_setopt($curl_yes, CURLOPT_TIMEOUT, 5);
                                      curl_setopt($curl_yes, CURLOPT_CONNECTTIMEOUT, 10);

                                      $response = curl_exec($curl_yes);
                                      $httpYes = curl_getinfo($curl_yes, CURLINFO_HTTP_CODE);
                                      $err = curl_error($curl_yes);
                                      $curl_infos = curl_getinfo($curl_yes);
                                      // echo $curl_infos;
                                      // var_dump($curl_infos);
                                      // curl_close($curl_yes);

                                      if ($httpYes != 200) {
                                        // echo "<br>cURL Error #:" . $err ."<br>";
                                        // echo "<br>cURL Error Code#:" . $httpYes ."<br>";
                                        $array = array(                       //FOR SALES PRO
                                          "FirstName" => $f_name,
                                          "LastName" => $l_name,
                                          "Mobile_Number" => $mobile,
                                          "City" => $location,
                                          "Employment_Type" => $employmentType,
                                          "Employer_Name" => $companyName,
                                          "Date_of_Birth" => $dob,
                                          "Monthly_Salary" => $income,
                                          "Loan_Amount" => $requiredLoanAmount,
                                          "Monthly_Obligation" => $monthlyObligation,
                                          "Pincode" => $userPincode,
                                          "Address_Line_1" => $address1,
                                          "Address_Line_2" => $address2,
                                          "Area_Locality" => $area,
                                          "PAN" => $userPAN,
                                          "Personal_EmailID" => $email,
                                          "Customer_Id" => $customer_id,
                                          "Partner" => "Null"
                                        );

                                        $data_string_leads_api = json_encode($array);
                                        $curl = curl_init();
                                        // $tokenLogin = "Authorization: ". $tokenLogin;
                                        $loginURL = "https://findsoftware4u.com/crm/wapp/get-leads";
                                        $header_js_login = array('Content-Type: application/json');
                                        // print_r ($header_js_login);
                                        //set cURL options
                                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                        curl_setopt($curl, CURLOPT_URL, $loginURL);
                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($curl, CURLOPT_POST, 1);
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string_leads_api);
                                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header_js_login);
                                        curl_setopt($curl, CURLOPT_VERBOSE, 1);
                                        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

                                        //Execute cURL
                                        $curl_response = curl_exec($curl);
                                        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                        // $ssl = curl_getinfo($curl,CURLINFO_SSL_ENGINES);
                                        // curl info test
                                        $curl_ind = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
                                        $curl_ifo = curl_getinfo($curl);
                                        $curl_ifo = json_encode($curl_ifo, JSON_PRETTY_PRINT);
                                        $curl_response_salespro = $curl_response;
                                        // echo $httpCode;
                                        // echo "<br>";
                                        // echo $curl_response;
                                        // echo "<br>";
                                        // echo "<br>";
                                        curl_close($curl); //SEND LEADS TO SALES PRO
                                        $http = $httpYes . " " . $httpCode;
                                        $curl_response_lead = "Error #- " . $err . ". Response -#" . $response;
                                      } elseif ($httpYes == 200) {
                                        echo $response;
                                        $array = array(                       //FOR SALES PRO
                                          "FirstName" => $f_name,
                                          "LastName" => $l_name,
                                          "Mobile_Number" => $mobile,
                                          "City" => $location,
                                          "Employment_Type" => $employmentType,
                                          "Employer_Name" => $companyName,
                                          "Date_of_Birth" => $dob,
                                          "Monthly_Salary" => $income,
                                          "Loan_Amount" => $requiredLoanAmount,
                                          "Monthly_Obligation" => $monthlyObligation,
                                          "Pincode" => $userPincode,
                                          "Address_Line_1" => $address1,
                                          "Address_Line_2" => $address2,
                                          "Area_Locality" => $area,
                                          "PAN" => $userPAN,
                                          "Personal_EmailID" => $email,
                                          "Customer_Id" => $customer_id,
                                          "Partner" => $api_category
                                        );

                                        $data_string_leads_api = json_encode($array);

                                        $curl = curl_init();
                                        // $tokenLogin = "Authorization: ". $tokenLogin;
                                        $loginURL = "https://findsoftware4u.com/crm/wapp/get-leads";
                                        $header_js_login = array('Content-Type: application/json');
                                        // print_r ($header_js_login);
                                        //set cURL options
                                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                        curl_setopt($curl, CURLOPT_URL, $loginURL);
                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($curl, CURLOPT_POST, 1);
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string_leads_api);
                                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header_js_login);
                                        curl_setopt($curl, CURLOPT_VERBOSE, 1);
                                        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

                                        //Execute cURL
                                        $curl_response = curl_exec($curl);
                                        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                        // $ssl = curl_getinfo($curl,CURLINFO_SSL_ENGINES);
                                        // curl info test
                                        $curl_ind = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
                                        $curl_ifo = curl_getinfo($curl);
                                        $curl_ifo = json_encode($curl_ifo, JSON_PRETTY_PRINT);
                                        $curl_response_salespro = $curl_response;
                                        // echo $httpCode;
                                        // echo "<br>";
                                        // echo $curl_response;
                                        // echo "<br>";
                                        // echo "<br>";
                                        curl_close($curl); //SEND LEADS TO SALES PRO
                                        $http = $httpYes . " " . $httpCode;
                                        $curl_response_lead = $response;
                                      }


                                      // YES BANK API INTRIGATION


                                      if (isset($_POST['data'])) {
                                        sleep(5);
                                        $_SESSION['data'] = json_encode($arraySession);
                                        // $host         = "localhost";
                                        // $dbusername   = "root";
                                        // $dbpassword   =   "";
                                        // $dbname       = "test";

                                        //----FOR SERVER ------//
                                        $host         = "localhost:3306";
                                        $dbusername   = "bn_wordpress";
                                        $dbpassword   = "62945cc1f5";
                                        $dbname       = "bitnami_wordpress_custom";


                                        ///*****------END------//
                                        //creat connection
                                        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                        if (mysqli_connect_error()) {
                                          // die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                          echo "Sorry! Internal Exception. Try again Later.";
                                        } else {

                                          $errors = array();
                                          $query = "SELECT * FROM wp_personal_loan WHERE sessionToken='$token' LIMIT 1";
                                          $result = mysqli_query($conn, $query);
                                          $tokenS = mysqli_fetch_assoc($result);
                                          if ($tokenS) { // if user exists
                                            if ($tokenS['sessionToken'] === $token) {
                                              array_push($errors, "Token already exists");
                                            }
                                          }


                                          if (count($errors) == 0) {
                                            $sql = "INSERT INTO wp_personal_loan (first_name , last_name , email , mobile , dob , employmentType , netmonthlyIncome ,
                                requiredLoanAmount , location ,
                                companyName , annualTurnover , bankrelation, userPincode , state, userPAN , requestingPage , userIp , userBrowser, lead_date , requestCategory
                                , agreePolicy, api_response ,httpCode , sessionToken,  address1, utm_source , utm_camp, utm_medium , jsonData)
                            VALUES ('$f_name' , '$l_name' , '$email' , '$mobile' , '$dob_db' , '$employmentType' ,  '$netmonthlyIncome' , '$requiredLoanAmount' , '$location' ,
                                '$companyName' , '$annualTurnover' ,  '$bankrelation', '$userPincode' , '$userState' , '$userPAN' , '$url' , '$userIp' , '$userBrowser',
                                '$time' , '$requestCategory' , '$agreePolicy', '$curl_response_lead' , '$http' , '$token' , '$address1' ,	'$utmSource' , 	'$utmCampaign' ,'$utmMedium', '$curl_response_salespro')";
                                            if ($conn->query($sql)) {
                                              // echo '<script>window.location.href = "https://www.freemi.in/loans/request-success?STATUS=Y";</script>';
                                              wp_redirect(bloginfo('url') . "/personal-loan-online/bank-eligibility/");
                                              // echo "Insert";

                                              $mydbLogin = new wpdb('bn_wordpress', '62945cc1f5', 'bitnami_wordpress_custom', 'localhost:3306');   //Server
                                              $rowsLogin = $mydbLogin->get_results("SELECT * FROM api WHERE `category` = 'registration'");
                                              foreach ($rowsLogin as $object) {
                                                $loginURL = $object->url;
                                                $tokenLogin = $object->token;
                                              }
                                              $curl = curl_init();
                                              $tokenLogin = "Authorization: " . $tokenLogin;

                                              $header_js_login = array('Content-Type: application/json', $tokenLogin);
                                              //print_r ($header_js_login);
                                              //set cURL options
                                              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                              curl_setopt($curl, CURLOPT_URL, $loginURL);
                                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                              curl_setopt($curl, CURLOPT_POST, 1);
                                              curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                              curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                              curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string_login_api);
                                              curl_setopt($curl, CURLOPT_HTTPHEADER, $header_js_login);
                                              curl_setopt($curl, CURLOPT_VERBOSE, 1);
                                              curl_setopt($curl, CURLOPT_TIMEOUT, 5);

                                              //Execute cURL
                                              $curl_response = curl_exec($curl);
                                              $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                              // $ssl = curl_getinfo($curl,CURLINFO_SSL_ENGINES);
                                              // curl info test
                                              $curl_ind = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
                                              $curl_ifo = curl_getinfo($curl);
                                              $curl_ifo = json_encode($curl_ifo, JSON_PRETTY_PRINT);

                                              if ($httpCode == 200) {
                                                $host         = "localhost:3306";
                                                $dbusername   = "bn_wordpress";
                                                $dbpassword   = "62945cc1f5";
                                                $dbname       = "bitnami_wordpress_custom";
                                                $conn_login = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                                if (mysqli_connect_error()) {
                                                  die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                                } else {
                                                  $sql_login = "INSERT INTO registrationResponse (uniqId , code , response , json_data)
                                VALUES ('$token' ,'$httpCode' , '$curl_response' , '$data_string_login_api')";
                                                  if ($conn_login->query($sql_login)) {
                                                    echo "<br>Registration Success";
                                                  } else {
                                                    // code...
                                                    echo "<br>Error: " . $sql_login . "<br>" . $conn_login->error;
                                                  }
                                                }

                                                echo "<br><br><br>HTTP CODE IS :   <strong>" . $httpCode . "</strong>";
                                                echo "<br>Response --->" . $curl_response;  //Output server response
                                              } else {
                                                $host         = "localhost:3306";
                                                $dbusername   = "bn_wordpress";
                                                $dbpassword   = "62945cc1f5";
                                                $dbname       = "bitnami_wordpress_custom";
                                                $conn_login = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                                if (mysqli_connect_error()) {
                                                  die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                                } else {
                                                  $sql_login = "INSERT INTO registrationResponse (uniqId , code , response , json_data)
                                VALUES ('$token' ,'$httpCode' , '$curl_response' , '$data_string_login_api')";
                                                  if ($conn_login->query($sql_login)) {
                                                    echo "Registration Faill";
                                                  } else {
                                                    // code...
                                                    echo "<br>Error: " . $sql_login . "<br>" . $conn_login->error;
                                                  }
                                                }
                                                echo "<br><br> <br>Check Your code";
                                                echo "<br>HTTP CODE IS:<strong>" . $httpCode . "</strong>";
                                                echo $curl_response; //Output server response
                                              }
                                              // Close cURL connection
                                              curl_close($curl);
                                            } else {
                                              echo "<br>Error: " . $sql . "<br>" . $conn->error;
                                              // echo '<div class="alert alert-danger mt-0 mb-3">Internal Exception. Please Try Again Later.</div>';
                                              wp_redirect(bloginfo('url') . "/personal-loan-online/bank-eligibility/");
                                            }
                                            $conn->close();
                                          } else {
                                            wp_redirect(bloginfo('url') . "/personal-loan-online/bank-eligibility/");
                                            echo "Token Exists";
                                            // echo '<script>window.location.href = "https://www.freemi.in/loans/request-success?STATUS=Y";</script>';

                                          }
                                        }
                                      }
                                    }


                                    /*----------------

FOR CUSTOMER REGISTRTION
------------------*/

                                    add_action('admin_post_add_user_reg', 'prefix_admin_add_user_reg');
                                    add_action('admin_post_nopriv_add_user_reg', 'prefix_admin_add_user_reg');
                                    function prefix_admin_add_user_reg()
                                    {
                                      $f_name             = $_POST['f_name'];
                                      $l_name             = $_POST['l_name'];
                                      $mobile             = $_POST['mobile'];
                                      $email              = $_POST['email'];
                                      $location           = $_POST['location'];
                                      $userPAN            = strtoupper($_POST['userPAN']);
                                      $userIp             = $_POST['userIp'];
                                      $userBrowser        =  $_POST['userBrowser'];
                                      $time               = $_POST['lead_date'];
                                      $requestCategory    = $_POST['requestCategory'];
                                      $userState          = $_POST['userState'];
                                      $userPincode        = $_POST['userPincode'];
                                      $token              = $_POST['uniqid'];

                                      $arrayLoginApi = array(
                                        "fullName" => $f_name . " " . $l_name,
                                        "fname" => $f_name,
                                        "mname" => "",
                                        "lname" => $l_name,
                                        "password" => "",
                                        "email" => $email,
                                        "mobile" => $mobile,
                                        "customerID" => "",
                                        "registrationref" => "LEAD_DATA",
                                        "requestingip" => $userIp,
                                        "clientbrowserdetails" => $userBrowser,
                                        "pan" => $userPAN,
                                        "mobileimei" => "",
                                        "address1" => "",
                                        "address2" => "",
                                        "address3" => "",
                                        "city" => $location,
                                        "state" => $userState,
                                        "country" => "IN",
                                        "pincode" => $userPincode,
                                        "leadtype" => $requestCategory,
                                        "mobileidvalid" => "N",
                                        "emailidvalid" => "N",
                                        "panvalid" => "N",
                                        "faircenttype" => "N"
                                      );
                                      $data_string_login_api = json_encode($arrayLoginApi);
                                      $mydbLogin = new wpdb('bn_wordpress', '62945cc1f5', 'bitnami_wordpress_custom', 'localhost:3306');   //Server
                                      $rowsLogin = $mydbLogin->get_results("SELECT * FROM api WHERE `category` = 'registration'");
                                      foreach ($rowsLogin as $object) {
                                        $loginURL = $object->url;
                                        $tokenLogin = $object->token;
                                      }
                                      $curl = curl_init();
                                      $tokenLogin = "Authorization: " . $tokenLogin;

                                      $header_js_login = array('Content-Type: application/json', $tokenLogin);
                                      // print_r ($header_js_login);
                                      //set cURL options
                                      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                      curl_setopt($curl, CURLOPT_URL, $loginURL);
                                      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                      curl_setopt($curl, CURLOPT_POST, 1);
                                      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string_login_api);
                                      curl_setopt($curl, CURLOPT_HTTPHEADER, $header_js_login);
                                      curl_setopt($curl, CURLOPT_VERBOSE, 1);
                                      curl_setopt($curl, CURLOPT_TIMEOUT, 5);

                                      //Execute cURL
                                      $curl_response = curl_exec($curl);
                                      $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                      // $ssl = curl_getinfo($curl,CURLINFO_SSL_ENGINES);
                                      // curl info test
                                      $curl_ind = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
                                      $curl_ifo = curl_getinfo($curl);
                                      $curl_ifo = json_encode($curl_ifo, JSON_PRETTY_PRINT);

                                      if ($httpCode == 200) {
                                        $host         = "localhost:3306";
                                        $dbusername   = "bn_wordpress";
                                        $dbpassword   = "62945cc1f5";
                                        $dbname       = "bitnami_wordpress_custom";
                                        $conn_login = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                        if (mysqli_connect_error()) {
                                          die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                        } else {
                                          $sql_login = "INSERT INTO registrationResponse (uniqId , code , response , json_data)
                VALUES ('$token' ,'$httpCode' , '$curl_response' , '$data_string_login_api')";
                                          if ($conn_login->query($sql_login)) {
                                            echo "Registration Success";
                                          } else {
                                            // code...
                                            echo "<br>Error: " . $sql_login . "<br>" . $conn_login->error;
                                          }
                                        }

                                        // echo "<br><br><br>HTTP CODE IS :   <strong>" . $httpCode. "</strong>";
                                        // echo "<br>Response --->" . $curl_response;  //Output server response
                                      } else {
                                        $host         = "localhost:3306";
                                        $dbusername   = "bn_wordpress";
                                        $dbpassword   = "62945cc1f5";
                                        $dbname       = "bitnami_wordpress_custom";
                                        $conn_login = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                        if (mysqli_connect_error()) {
                                          die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                        } else {
                                          $sql_login = "INSERT INTO registrationResponse (uniqId , code , response , json_data)
                VALUES ('$token' ,'$httpCode' , '$curl_response' , '$data_string_login_api')";
                                          if ($conn_login->query($sql_login)) {
                                            echo "Registration Faill";
                                          } else {
                                            // code...
                                            // echo "<br>Error: " . $sql_login . "<br>" . $conn_login->error;
                                          }
                                        }
                                        //   echo "<br><br> <br>Check Your code";
                                        // echo "<br>HTTP CODE IS:<strong>" . $httpCode. "</strong>";
                                        echo $curl_response; //Output server response
                                      }
                                      // Close cURL connection
                                      curl_close($curl);
                                    }



                                    /*----------------

FOR MEMBERSHIP
------------------*/

                                    add_action('admin_post_add_membership', 'prefix_admin_add_membership');
                                    add_action('admin_post_nopriv_add_membership', 'prefix_admin_add_membership');
                                    function prefix_admin_add_membership()
                                    {

                                      $customerId                          = $_POST['customerId'];
                                      $uniqid                              = $_POST['uniqid'];
                                      $profileRegRequired                  = $_POST['profileRegRequired'];
                                      $customerRegistered                  = $_POST['customerRegistered'];
                                      $invName                             = $_POST['invName'];
                                      $dividendPayMode                     = $_POST['dividendPayMode'];
                                      $customerSignature1                  = $_POST['customerSignature1'];
                                      $customerSignature2                  = $_POST['customerSignature2'];
                                      $holdingMode                         = $_POST['holdingMode'];
                                      $mobileverified                      = $_POST['mobileverified'];
                                      $emailverified                       = $_POST['emailverified'];
                                      $fname                               = $_POST['fname'];
                                      $mname                               = $_POST['mname'];
                                      $lname                               = $_POST['lname'];
                                      $mobile                              = $_POST['mobile'];
                                      $email                               = $_POST['email'];
                                      $pan1                                = $_POST['pan1'];
                                      $gender                              = $_POST['gender'];
                                      $customerdob                         = $_POST['customerdob'];
                                      $occupation                          = $_POST['occupation'];
                                      $addressDetailsaddress1             = $_POST['addressDetailsaddress1'];
                                      $addressDetailsaddress2             = $_POST['addressDetailsaddress2'];
                                      $addressDetailsaddress3             = $_POST['addressDetailsaddress3'];
                                      $addressDetailsstate                = $_POST['addressDetailsstate'];
                                      $addressDetailscity                 = $_POST['addressDetailscity'];
                                      $addressDetailspinCode              = $_POST['addressDetailspinCode'];
                                      $bankDetailsaccountNumber           = $_POST['bankDetailsaccountNumber'];
                                      $bankDetailsifscCode                = $_POST['bankDetailsifscCode'];
                                      $bankDetailsaccountType             = $_POST['bankDetailsaccountType'];
                                      $bankDetailsbankName                = $_POST['bankDetailsbankName'];
                                      $bankDetailsbankBranch              = $_POST['bankDetailsbankBranch'];
                                      $bankDetailsbankAddress             = $_POST['bankDetailsbankAddress'];
                                      $bankDetailsbankCity                = $_POST['bankDetailsbankCity'];
                                      $bankDetailsbranchState             = $_POST['bankDetailsbranchState'];
                                      $nomineenomineeName                 = $_POST['nomineenomineeName'];
                                      $nomineenomineeRelation             = $_POST['nomineenomineeRelation'];
                                      $nomineeisNomineeMinor              = $_POST['nomineeisNomineeMinor'];
                                      $nomineenomineeDOB                  = $_POST['nomineenomineeDOB'];
                                      $nomineenomineeGuardian             = $_POST['nomineenomineeGuardian'];
                                      $fatcaDetailsplaceOfBirth           = $_POST['addressDetailscity'];
                                      $fatcaDetailscountryOfBirth         = $_POST['fatcaDetailscountryOfBirth'];
                                      $fatcaDetailsfatherName             = $_POST['fatcaDetailsfatherName'];
                                      $fatcaDetailsspouseName             = $_POST['fatcaDetailsspouseName'];
                                      $fatcaDetailsincomeSlab             = $_POST['fatcaDetailsincomeSlab'];
                                      $fatcaDetailswealthSource           = $_POST['fatcaDetailswealthSource'];
                                      $fatcaDetailspoliticalExposedPerson = $_POST['fatcaDetailspoliticalExposedPerson'];
                                      $fatcaDetailsusCitizenshipCheck     = $_POST['_fatcaDetailsusCitizenshipCheck'];
                                      $ubo                                 = $_POST['ubo'];
                                      $csrf                                = $_POST['_wpnonce'];
                                      $userIp                              = $_POST['userIp'];
                                      $userBrowser                         = $_POST['userBrowser'];

                                      $arrayLoginApi = array(
                                        "fullName" => $fname . " " . $lname,
                                        "fname" => $fname,
                                        "mname" => $mname,
                                        "lname" => $lname,
                                        "password" => "",
                                        "email" => $email,
                                        "mobile" => $mobile,
                                        "customerID" => "",
                                        "registrationref" => "LEAD_DATA",
                                        "requestingip" => $userIp,
                                        "clientbrowserdetails" => $userBrowser,
                                        "pan" => $pan1,
                                        "mobileimei" => "",
                                        "address1" => $addressDetailsaddress1,
                                        "address2" => $addressDetailsaddress2,
                                        "address3" => $addressDetailsaddress3,
                                        "city" => $addressDetailscity,
                                        "state" => $addressDetailsstate,
                                        "country" => "IN",
                                        "pincode" => $addressDetailspinCode,
                                        "leadtype" => "MB",
                                        "mobileidvalid" => $mobileverified,
                                        "emailidvalid" => $emailverified,
                                        "panvalid" => "N",
                                        "faircenttype" => "N"
                                      );
                                      $data_string_login_api = json_encode($arrayLoginApi); //LOGIN IN FREEMI


                                      $mydbLogin = new wpdb('bn_wordpress', '62945cc1f5', 'bitnami_wordpress_custom', 'localhost:3306');   //Server
                                      $rowsLogin = $mydbLogin->get_results("SELECT * FROM api WHERE `category` = 'registration'");
                                      foreach ($rowsLogin as $object) {
                                        $loginURL = $object->url;
                                        $tokenLogin = $object->token;
                                      }
                                      $curl = curl_init();
                                      $tokenLogin = "Authorization: " . $tokenLogin;

                                      $header_js_login = array('Content-Type: application/json', $tokenLogin);
                                      //print_r ($header_js_login);
                                      //set cURL options
                                      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                      curl_setopt($curl, CURLOPT_URL, $loginURL);
                                      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                      curl_setopt($curl, CURLOPT_POST, 1);
                                      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string_login_api);
                                      curl_setopt($curl, CURLOPT_HTTPHEADER, $header_js_login);
                                      curl_setopt($curl, CURLOPT_VERBOSE, 1);
                                      curl_setopt($curl, CURLOPT_TIMEOUT, 5);

                                      //Execute cURL
                                      $curl_response = curl_exec($curl);
                                      $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                      if ($httpCode == 200) {
                                        $host         = "localhost:3306";
                                        $dbusername   = "bn_wordpress";
                                        $dbpassword   = "62945cc1f5";
                                        $dbname       = "bitnami_wordpress_custom";
                                        $conn_login = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                        if (mysqli_connect_error()) {
                                          die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                        } else {
                                          $sql_login = "INSERT INTO registrationResponse (uniqId , code , response , json_data)
                                            VALUES ('$uniqid' ,'$httpCode' , '$curl_response' , '$data_string_login_api')";
                                          if ($conn_login->query($sql_login)) {
                                            // echo "<br>Registration Success";
                                          } else {
                                            // code...
                                            // echo "<br>Error: " . $sql_login . "<br>" . $conn_login->error;
                                          }
                                          // FOR API IN MEMBERSHIP
                                          $arrayMemberApi = array(
                                            'customerId'                          => $customerId,
                                            'uniqid'                              => $uniqid,
                                            'profileRegRequired'                  => $profileRegRequired,
                                            'customerRegistered'                  => "Y",
                                            'invName'                             => $invName,
                                            'dividendPayMode'                     => $dividendPayMode,
                                            'customerSignature1'                  => $customerSignature1,
                                            'customerSignature2'                  => $customerSignature2,
                                            'holdingMode'                         => $holdingMode,
                                            'mobileverified'                      => $mobileverified,
                                            'emailverified'                       => $emailverified,
                                            'fname'                               => $fname,
                                            'mname'                               => $mname,
                                            'lname'                               => $lname,
                                            'mobile'                              => $mobile,
                                            'email'                               => $email,
                                            'pan1'                                => $pan1,
                                            'gender'                              => $gender,
                                            'customerdob'                         => $customerdob,
                                            'occupation'                          => $occupation,
                                            'addressDetailsaddress1'             => $addressDetailsaddress1,
                                            'addressDetailsaddress2'             => $addressDetailsaddress2,
                                            'addressDetailsaddress3'             => $addressDetailsaddress3,
                                            'addressDetailsstate'                => $addressDetailsstate,
                                            'addressDetailscity'                 => $addressDetailscity,
                                            'addressDetailspinCode'              => $addressDetailspinCode,
                                            'bankDetailsaccountNumber'           => $bankDetailsaccountNumber,
                                            'bankDetailsifscCode'                => $bankDetailsifscCode,
                                            'bankDetailsaccountType'             => $bankDetailsaccountType,
                                            'bankDetailsbankName'                => $bankDetailsbankName,
                                            'bankDetailsbankBranch'              => $bankDetailsbankBranch,
                                            'bankDetailsbankAddress'             => $bankDetailsbankAddress,
                                            'bankDetailsbankCity'                => $bankDetailsbankCity,
                                            'bankDetailsbranchState'             => $bankDetailsbranchState,
                                            'nomineenomineeName'                 => $nomineenomineeName,
                                            'nomineenomineeRelation'             => $nomineenomineeRelation,
                                            'nomineeisNomineeMinor'              => $nomineeisNomineeMinor,
                                            'nomineenomineeDOB'                  => $nomineenomineeDOB,
                                            'nomineenomineeGuardian'             => $nomineenomineeGuardian,
                                            'fatcaDetailsplaceOfBirth'           => $fatcaDetailsplaceOfBirth,
                                            'fatcaDetailscountryOfBirth'         => $fatcaDetailscountryOfBirth,
                                            'fatcaDetailsfatherName'             => $fatcaDetailsfatherName,
                                            'fatcaDetailsspouseName'             => $fatcaDetailsspouseName,
                                            'fatcaDetailsincomeSlab'             => $fatcaDetailsincomeSlab,
                                            'fatcaDetailswealthSource'           => $fatcaDetailswealthSource,
                                            'fatcaDetailspoliticalExposedPerson' => $fatcaDetailspoliticalExposedPerson,
                                            'fatcaDetailsusCitizenshipCheck'     => $fatcaDetailsusCitizenshipCheck,
                                            'ubo'                                 => $ubo,
                                            'csrf'                                => $csrf,
                                            'userIp'                              => $userIp,
                                            'userBrowser'                         => $userBrowser,
                                          );
                                          $customerRegistered                  = "Y";
                                          $data_json_member_api = json_encode($arrayMemberApi);
                                          // print_r($data_json_member_api);
                                          // MEMBERSHIPAPI JSON BUILD
                                          // Memeber API CALL
                                          $curlM = curl_init();
                                          $memberURL = "https://findsoftware4u.com/crm/wapp/get-member-leads";
                                          $header_js_member = array('Content-Type: application/json');
                                          curl_setopt($curlM, CURLOPT_CUSTOMREQUEST, "POST");
                                          curl_setopt($curlM, CURLOPT_URL, $memberURL);
                                          curl_setopt($curlM, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curlM, CURLOPT_POST, 1);
                                          curl_setopt($curlM, CURLOPT_SSL_VERIFYHOST, 0);
                                          curl_setopt($curlM, CURLOPT_SSL_VERIFYPEER, 0);
                                          curl_setopt($curlM, CURLOPT_POSTFIELDS, $data_json_member_api);
                                          curl_setopt($curlM, CURLOPT_HTTPHEADER, $header_js_member);
                                          curl_setopt($curlM, CURLOPT_VERBOSE, 1);
                                          curl_setopt($curlM, CURLOPT_TIMEOUT, 5);

                                          //Execute cURL
                                          $curl_response_m = curl_exec($curlM);
                                          $httpCodeM = curl_getinfo($curlM, CURLINFO_HTTP_CODE);

                                          $_SESSION['dataMemBer'] = $curl_response_m;
                                          // print_r($_SESSION['dataMemBer']);
                                          curl_close($curlM);

                                          // Order razorpay
                                          $arrayOrder = array(
                                            'receipt' => 'Receipt#' . $customerId,
                                            'amount' => '499',
                                            'currency' => 'INR',
                                            'notes' => array('customerReference' => $customerId, 'CustomerMobile' => $mobile)

                                          );
                                          $data_string_order_api = json_encode($arrayOrder); //LOGIN IN FREEMI

                                          $curlO = curl_init();
                                          $loginOrderURL = "https://api.razorpay.com/v1/orders";
                                          $header_js_Order = array('Accept: application/json', 'Content-Type: application/json');
                                          print_r($header_js_Order);
                                          //set cURL options
                                          curl_setopt($curlO, CURLOPT_CUSTOMREQUEST, "POST");
                                          curl_setopt($curlO, CURLOPT_URL, $loginOrderURL);
                                          curl_setopt($curlO, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curlO, CURLOPT_POST, 1);
                                          curl_setopt($curlO, CURLOPT_POSTFIELDS, $data_string_order_api);
                                          curl_setopt($curlO, CURLOPT_USERPWD, "rzp_test_xDskoVbdRxNOez:5VoK3dCCvkN6UXeliOi4WjV3");
                                          curl_setopt($curlO, CURLOPT_HTTPHEADER, $header_js_Order);

                                          //Execute cURL
                                          $curl_response_order = curl_exec($curlO);
                                          $httpCode = curl_getinfo($curlO, CURLINFO_HTTP_CODE);
                                          $curl_ifo = json_encode($curl_response, JSON_PRETTY_PRINT);

                                          if ($httpCode == 200) {
                                            $_SESSION['dataOrder'] = $curl_response_order;
                                          } else {
                                            $_SESSION['dataOrder'] = $curl_response_order;
                                          }
                                          curl_close($curlO);


                                          // SQL FOR MEMBER CODE
                                          $sql_member = "INSERT INTO wp_membership (sessionToken , customerId ,profileRegRequired, customerRegistered , mobileverified, mobile, response , http, api_name , dbName , csrf , paymentResponse)
                                            VALUES ('$uniqid' , '$customerId', '$profileRegRequired', '$customerRegistered', '$mobileverified', '$mobile', '$curl_response_m','$httpCodeM' , 'http://localhost/fundsonline/wapp/get-member-leads' , 'back_office' , '$csrf' , '$curl_response_order')";
                                          if ($conn_login->query($sql_member)) {
                                            // echo "<br>Member ship entry Success";
                                          } else {
                                            // code...
                                            // echo "<br>Error: " . $sql_member . "<br>" . $conn_login->error;
                                          }

                                          // wp_redirect(bloginfo('url'));
                                          wp_redirect(bloginfo('url') . "/success-page/");
                                          // SQL FOR MEMBER CODE
                                        }

                                        // echo "<br><br><br>HTTP CODE IS :   <strong>" . $httpCode . "</strong>";
                                        // echo "<br>Response --->" . $curl_response;  //Output server response
                                      } else {   //API CHECK REGISTRATION

                                        $host         = "localhost:3306";
                                        $dbusername   = "bn_wordpress";
                                        $dbpassword   = "62945cc1f5";
                                        $dbname       = "bitnami_wordpress_custom";
                                        $conn_login = new mysqli($host, $dbusername, $dbpassword, $dbname);

                                        if (mysqli_connect_error()) {
                                          die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
                                        } else {
                                          $sql_login = "INSERT INTO registrationResponse (uniqId , code , response , json_data)
                                            VALUES ('$uniqid' ,'$httpCode' , '$curl_response' , '$data_string_login_api')";
                                          if ($conn_login->query($sql_login)) {
                                            // echo "Registration Faill";
                                          } else {
                                            // code...
                                            // echo "<br>Error: " . $sql_login . "<br>" . $conn_login->error;
                                          }
                                          // FOR API IN MEMBERSHIP
                                          $arrayMemberApi = array(
                                            'customerId'                          => $customerId,
                                            'uniqid'                              => $uniqid,
                                            'profileRegRequired'                  => $profileRegRequired,
                                            'customerRegistered'                  => "N",
                                            'invName'                             => $invName,
                                            'dividendPayMode'                     => $dividendPayMode,
                                            'customerSignature1'                  => $customerSignature1,
                                            'customerSignature2'                  => $customerSignature2,
                                            'holdingMode'                         => $holdingMode,
                                            'mobileverified'                      => $mobileverified,
                                            'emailverified'                       => $emailverified,
                                            'fname'                               => $fname,
                                            'mname'                               => $mname,
                                            'lname'                               => $lname,
                                            'mobile'                              => $mobile,
                                            'email'                               => $email,
                                            'pan1'                                => $pan1,
                                            'gender'                              => $gender,
                                            'customerdob'                         => $customerdob,
                                            'occupation'                          => $occupation,
                                            'addressDetailsaddress1'             => $addressDetailsaddress1,
                                            'addressDetailsaddress2'             => $addressDetailsaddress2,
                                            'addressDetailsaddress3'             => $addressDetailsaddress3,
                                            'addressDetailsstate'                => $addressDetailsstate,
                                            'addressDetailscity'                 => $addressDetailscity,
                                            'addressDetailspinCode'              => $addressDetailspinCode,
                                            'bankDetailsaccountNumber'           => $bankDetailsaccountNumber,
                                            'bankDetailsifscCode'                => $bankDetailsifscCode,
                                            'bankDetailsaccountType'             => $bankDetailsaccountType,
                                            'bankDetailsbankName'                => $bankDetailsbankName,
                                            'bankDetailsbankBranch'              => $bankDetailsbankBranch,
                                            'bankDetailsbankAddress'             => $bankDetailsbankAddress,
                                            'bankDetailsbankCity'                => $bankDetailsbankCity,
                                            'bankDetailsbranchState'             => $bankDetailsbranchState,
                                            'nomineenomineeName'                 => $nomineenomineeName,
                                            'nomineenomineeRelation'             => $nomineenomineeRelation,
                                            'nomineeisNomineeMinor'              => $nomineeisNomineeMinor,
                                            'nomineenomineeDOB'                  => $nomineenomineeDOB,
                                            'nomineenomineeGuardian'             => $nomineenomineeGuardian,
                                            'fatcaDetailsplaceOfBirth'           => $fatcaDetailsplaceOfBirth,
                                            'fatcaDetailscountryOfBirth'         => $fatcaDetailscountryOfBirth,
                                            'fatcaDetailsfatherName'             => $fatcaDetailsfatherName,
                                            'fatcaDetailsspouseName'             => $fatcaDetailsspouseName,
                                            'fatcaDetailsincomeSlab'             => $fatcaDetailsincomeSlab,
                                            'fatcaDetailswealthSource'           => $fatcaDetailswealthSource,
                                            'fatcaDetailspoliticalExposedPerson' => $fatcaDetailspoliticalExposedPerson,
                                            'fatcaDetailsusCitizenshipCheck'     => $fatcaDetailsusCitizenshipCheck,
                                            'ubo'                                 => $ubo,
                                            'csrf'                                => $csrf,
                                            'userIp'                              => $userIp,
                                            'userBrowser'                         => $userBrowser,
                                          );
                                          $customerRegistered                  = "N";
                                          $data_json_member_api = json_encode($arrayMemberApi);
                                          // print_r($data_json_member_api);
                                          // MEMBERSHIPAPI JSON BUILD
                                          // Memeber API CALL
                                          $curlM = curl_init();
                                          $memberURL = "https://findsoftware4u.com/crm/wapp/get-member-leads";
                                          $header_js_member = array('Content-Type: application/json');
                                          curl_setopt($curlM, CURLOPT_CUSTOMREQUEST, "POST");
                                          curl_setopt($curlM, CURLOPT_URL, $memberURL);
                                          curl_setopt($curlM, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curlM, CURLOPT_POST, 1);
                                          curl_setopt($curlM, CURLOPT_SSL_VERIFYHOST, 0);
                                          curl_setopt($curlM, CURLOPT_SSL_VERIFYPEER, 0);
                                          curl_setopt($curlM, CURLOPT_POSTFIELDS, $data_json_member_api);
                                          curl_setopt($curlM, CURLOPT_HTTPHEADER, $header_js_member);
                                          curl_setopt($curlM, CURLOPT_VERBOSE, 1);
                                          curl_setopt($curlM, CURLOPT_TIMEOUT, 5);

                                          //Execute cURL
                                          $curl_response_m = curl_exec($curlM);
                                          $httpCodeM = curl_getinfo($curlM, CURLINFO_HTTP_CODE);

                                          $_SESSION['dataMemBer'] = $curl_response_m;
                                          // print_r($_SESSION['dataMemBer']);
                                          curl_close($curlM);

                                          // Order razorpay
                                          $arrayOrder = array(
                                            'receipt' => 'Receipt#' . $customerId,
                                            'amount' => '499',
                                            'currency' => 'INR',
                                            'notes' => array('customerReference' => $customerId, 'CustomerMobile' => $mobile)

                                          );
                                          $data_string_order_api = json_encode($arrayOrder); //LOGIN IN FREEMI

                                          $curlO = curl_init();
                                          $loginOrderURL = "https://api.razorpay.com/v1/orders";
                                          $header_js_Order = array('Accept: application/json', 'Content-Type: application/json');
                                          print_r($header_js_Order);
                                          //set cURL options
                                          curl_setopt($curlO, CURLOPT_CUSTOMREQUEST, "POST");
                                          curl_setopt($curlO, CURLOPT_URL, $loginOrderURL);
                                          curl_setopt($curlO, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curlO, CURLOPT_POST, 1);
                                          curl_setopt($curlO, CURLOPT_POSTFIELDS, $data_string_order_api);
                                          curl_setopt($curlO, CURLOPT_USERPWD, "rzp_test_xDskoVbdRxNOez:5VoK3dCCvkN6UXeliOi4WjV3");
                                          curl_setopt($curlO, CURLOPT_HTTPHEADER, $header_js_Order);

                                          //Execute cURL
                                          $curl_response_order = curl_exec($curlO);
                                          $httpCode = curl_getinfo($curlO, CURLINFO_HTTP_CODE);
                                          if ($httpCode == 200) {
                                            $_SESSION['dataOrder'] = $curl_response_order;
                                          } else {
                                            $_SESSION['dataOrder'] = $curl_response_order;
                                          }
                                          curl_close($curlO);


                                          // SQL FOR MEMBER CODE
                                          $sql_member = "INSERT INTO wp_membership (sessionToken , customerId ,profileRegRequired, customerRegistered , mobileverified, mobile, response , http, api_name , dbName , csrf , paymentResponse)
                                            VALUES ('$uniqid' , '$customerId', '$profileRegRequired', '$customerRegistered', '$mobileverified', '$mobile', '$curl_response_m','$httpCodeM' , 'http://localhost/fundsonline/wapp/get-member-leads' , 'back_office' , '$csrf' , '$curl_response_order')";
                                          if ($conn_login->query($sql_member)) {
                                            // echo "<br>Member ship entry Success";
                                          } else {
                                            // code...
                                            // echo "<br>Error: " . $sql_member . "<br>" . $conn_login->error;
                                          }

                                          // SQL FOR MEMBER CODE
                                        }
                                        // echo "API END ERROR";
                                        // echo "<br><br> <br>Check Your code";
                                        // echo "<br>HTTP CODE IS:<strong>" . $httpCode . "</strong>";
                                        // echo $curl_response; //Output server response
                                        // wp_redirect(bloginfo('url'));
                                        wp_redirect(bloginfo('url') . "/success-page/");
                                      }
                                      // Close cURL connection
                                      curl_close($curl);
                                    }
