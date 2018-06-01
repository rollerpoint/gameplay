<?php
defined( 'ABSPATH' ) or die();

// Check for uniqueness
if ( ! class_exists( 'Change_Author_Link_Structure' ) ) {
	// Main Plugin Class
	class Change_Author_Link_Structure {
		/**
		 * Block of rewrite rules that is used to change the link structure.
		 *
		 * @since 1.0
		 * @access private
		 */
		private static $rewrite_rules = array 
		(
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([0-9]+)/?$:index.php?author=$matches[2]',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/?$:index.php?author_name=(404-error)',			
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([0-9]+)/page/(\d*)/?$:index.php?author=$matches[2]&paged=$matches[3]',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/page/(\d*)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/page/(\d*)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/page/(\d*)/?$:index.php?author_name=(404-error)',		
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([0-9]+)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author=$matches[2]&feed=$matches[3]',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',		
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([0-9]+)/(feed|rdf|rss|rss2|atom)/?$:index.php?author=$matches[2]&feed=$matches[3]',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^([A-Za-z0-9/-]*)/AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',		
			'^AUTHOR_BASE/([0-9]+)/?$:index.php?author=$matches[1]',
			'^AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/?$:index.php?author_name=(404-error)',			
			'^AUTHOR_BASE/([0-9]+)/page/(\d*)/?$:index.php?author=$matches[1]&paged=$matches[2]',
			'^AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/page/(\d*)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/page/(\d*)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/page/(\d*)/?$:index.php?author_name=(404-error)',		
			'^AUTHOR_BASE/([0-9]+)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author=$matches[1]&feed=$matches[2]',
			'^AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/feed/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',		
			'^AUTHOR_BASE/([0-9]+)/(feed|rdf|rss|rss2|atom)/?$:index.php?author=$matches[1]&feed=$matches[2]',
			'^AUTHOR_BASE/([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)',
			'^AUTHOR_BASE/([A-Za-z0-9@_\.-]*)([A-Za-z@_\.-]+)([A-Za-z0-9@_\.-]*)/(feed|rdf|rss|rss2|atom)/?$:index.php?author_name=(404-error)'
		);
		
		/**
		 * Rewrite rules are added at plugin's activation.
		 *
		 * @since 0.0.2
		 */	
		static function activate( $networkwide ) {
			global $wpdb;
			// In case of network activation 
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $networkwide ) {
					$main_blog = $wpdb->blogid;
					$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
					foreach ( $blogids as $blogid ) {
						switch_to_blog( $blogid );
						Change_Author_Link_Structure::add_plugin_author_rewrite_rules();
					}
					switch_to_blog( $main_blog );
					return;
				}
			}
			// In case of single site activation
			Change_Author_Link_Structure::add_plugin_author_rewrite_rules();
		}
		
		/**
		 * At plugin's deactivation the rewrite rules are updated to the initial state.
		 * 
		 * @since 0.0.2
		 */
		static function deactivate( $networkwide ) {
			global $wpdb;		
			// In case of network activation
			if ( function_exists ( 'is_multisite' ) && is_multisite() ) {
				if ( $networkwide ) {
					$main_blog = $wpdb->blogid;
					$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
					foreach ( $blogids as $blogid ) {
						switch_to_blog( $blogid );	
						Change_Author_Link_Structure::remove_plugin_author_rewrite_rules();
						
						$options = get_option( 'cals_settings' );
			
						// check if the plugin's options for an author base are set
						if( $options != false && isset( $options['author_base'] ) && !empty($options['author_base']) ) {
						
							// ensure that after deactivation initial state is recreated
							global $wp_rewrite;
							$wp_rewrite->init(); 
							if( get_option( 'cals_author_base_buffer' ) == false ) {
								$wp_rewrite->author_base = 'author';
							} else {
								$wp_rewrite->author_base = get_option( 'cals_author_base_buffer' );
								delete_option( 'cals_author_base_buffer' );
							}
							
							$wp_rewrite->flush_rules();
						}
						
					}
					switch_to_blog( $main_blog );
					return;
				}
			}
			// In case of single site activation
			Change_Author_Link_Structure::remove_plugin_author_rewrite_rules();
		}
		
		/**
		 * The author's username is replaced with the ID in the link.
		 *
		 * @since 0.0.1
		 * 
		 * @param string $link Link in posts to the author's page.
		 * @param int $author_id Author's ID.
		 * @param string $author_nicename Author's username.
		 *
		 * @return string Modified link.
		 */
		function modify_link( $link, $author_id, $author_nicename ) {
    		$link = str_replace( $author_nicename, $author_id, $link );
    		return $link;
		}
		
		/**
		 * New rewrite rules, that allow ID's instead of usernames in the link to the author page, ared added.
		 * 
		 * @since 0.0.5
		 */
		static function add_plugin_author_rewrite_rules() {
			$rules = get_option( 'rewrite_rules' );
			
			if( $rules == false || empty( $rules ) ) {
				$rules = array();
			}
			
			$new_rules = array();
			
			foreach( Change_Author_Link_Structure::$rewrite_rules as $rule ) {
				$entries = explode( ':', $rule );
				$new_rules[str_replace( 'AUTHOR_BASE', 'author', $entries[0] )] = $entries[1];
			}
			
			$rules = array_merge( $new_rules, $rules );
			update_option( 'rewrite_rules', $rules );
		}		
		
		/**
		 * The plugin's rewrite rules are removed.
		 * 
		 * @since 0.0.3
		 */
		static function remove_plugin_author_rewrite_rules() {
			$rules = get_option( 'rewrite_rules' );
			$saved_author_base = get_option( 'cals_author_base' );
			
			foreach( Change_Author_Link_Structure::$rewrite_rules as $rule ) {
				$entries = explode( ':', $rule );
				unset( $rules[str_replace( 'AUTHOR_BASE', $saved_author_base, $entries[0] )]);
			}
			
			update_option( 'rewrite_rules', $rules );	
			
			delete_option( 'cals_author_base' );
		}
		
		/**
		 * Rewrite rules ared added after regeneration.
		 * 
		 * @since 0.0.1
		 *
		 * @param object $wp_rewrite Global object which contains rewrite rules.
		 */
		function add_author_rewrite_rules( $wp_rewrite ) {
			$saved_author_base = get_option( 'cals_author_base' );
			
			if( $saved_author_base == false || empty( $saved_author_base ) ) {
				return;
			} 
			
			$new_rules = array();
			
			foreach( Change_Author_Link_Structure::$rewrite_rules as $rule ) {
				$entries = explode( ':', $rule );
				$new_rules[str_replace( 'AUTHOR_BASE', $saved_author_base, $entries[0] )] = $entries[1];
			}
			
			$rules = $wp_rewrite->rules;
			if( !is_array( $rules ) ) {
				$rules = array();
			}
			
			$wp_rewrite->rules = array_merge( $new_rules, $rules );
		}
		
		/**
		 * At every page load it is checked if the plugin has been updated
		 * or the author base has changed.
		 *
		 * @since 0.2
		 */
		function load_filter() {
			global $wp_rewrite;
			
			// if author base is not set it is set to author
			if( get_option( 'cals_author_base' ) == false ) {
				add_option( 'cals_author_base', 'author' );
			}
			
			$options = get_option( 'cals_settings' );	
			$plugin_option = false;
			
			// save the previous author base
			$author_base_before = $wp_rewrite->author_base;
			
			// check if the plugin's options for an author base are set
			// if it is set the previous author base is overwritten
			// if not the buffer is removed
			if( $options != false && isset( $options['author_base'] ) && !empty($options['author_base']) ) {
				$plugin_option = true;
				$wp_rewrite->author_base = $options['author_base'];
			} else {
				if( get_option( 'cals_author_base_buffer' ) != false ) {
					delete_option( 'cals_author_base_buffer' );
				}
			}
			// if previous author base is deactivated 
			// the buffer is removed
			if( $author_base_before == 'author' ) {
				delete_option( 'cals_author_base_buffer' );
			}
			
			// saved author base
			$saved_author_base = get_option( 'cals_author_base' );
			
			// current author base
			$rewrite_author_base = $wp_rewrite->author_base;
			
			// if saved and current author base are not equal
			if( $rewrite_author_base != $saved_author_base )  {
				
				// if author base has been changed in the plugin's options
				// and the previous existing author base has not been buffered before
				// the current value for author base is saved
				if( $plugin_option ) {
					if ( get_option( 'cals_author_base_buffer' ) == false  && $author_base_before != 'author' ) {
						add_option( 'cals_author_base_buffer', $author_base_before );		
					}	
				}
				
				flush_rewrite_rules();
				$rules = get_option( 'rewrite_rules' );
				if( !is_array( $rules ) ) {
					$rules = array();
				}
			
				// Remove rules with old author base
				foreach( Change_Author_Link_Structure::$rewrite_rules as $rule ) {
					$entries = explode( ':', $rule );
					unset( $rules[str_replace( 'AUTHOR_BASE', $saved_author_base, $entries[0] )]);
				}
				
				$new_rules = array();
				
				// Set new rules with new author base
				foreach( Change_Author_Link_Structure::$rewrite_rules as $rule ) {
					$entries = explode( ':', $rule );
					$new_rules[str_replace( 'AUTHOR_BASE', $rewrite_author_base, $entries[0] )] = $entries[1];
				}
				
				$rules = array_merge( $new_rules, $rules );				
				update_option( 'rewrite_rules', $rules);

				update_option( 'cals_author_base', $rewrite_author_base );
			}
			
			// Update work to delete defined rules in version 0.1
			if( get_option( 'cals_version' ) == false ) {
				add_option( 'cals_version', '1.0' );
				
				$rules = get_option( 'rewrite_rules' );
				
				// Delete old plugin rewrite rules
				unset($rules['^([A-Za-z0-9/-]*)author/([A-Za-z0-9-]+)/?$']);
				unset($rules['^([A-Za-z0-9/-]*)author/([A-Za-z0-9-]+)/page/(\d*)/?$']);
				unset($rules['^([A-Za-z0-9/-]*)author/([A-Za-z0-9-]+)/feed/(feed|rdf|rss|rss2|atom)/?$']);
				unset($rules['^([A-Za-z0-9/-]*)author/([A-Za-z0-9-]+)/(feed|rdf|rss|rss2|atom)/?$']);
				
				update_option( 'rewrite_rules', $rules );
				flush_rewrite_rules();
			} else if( get_option( 'cals_version' == '0.2' ) ) {
				update_option( 'cals_version', '1.0' );
			}
		}
	}
}
?>
