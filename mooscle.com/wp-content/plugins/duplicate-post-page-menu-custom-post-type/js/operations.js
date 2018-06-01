// Make sure that jquery won't conflict with other jQuery script in anycase.

jQuery('.publishing-action').ready(function($){
	// Multiple variables are used to make single line short and readable.
	var dppmcBtName = duplicate_ppmc_ENG.dppmc_bt_name,
		menuName	  = $("[name='menu-name']").val(),
		menuLink	  = 'admin.php?action=duplicate_ppmc_menu_maker',
		menu_clone 	  = menuLink+'&name='+menuName,
		btClass	  = 'button button-primary button-large';

	// Add 'Duplicate' button next to menu 'Save' button.
	if ( '0' === ( duplicate_ppmc_ENG.enable_in_menu ) ) {
		$( '.publishing-action' ).
		append('<a name="DPPM_menu" class="'+
		btClass+'" href="'+menu_clone+'">'+dppmcBtName+'</a>');
	}
});

// Make sure that document(Webpage) is ready to fire the jQuery script.
jQuery(document).ready(function( $ ){

	/*	Get the option that user has selected for cloning	*/

	$( '[name="duplicate_ppmc_item_no"]' ).bind( 'input',function() {

		var	count	= $(this).val();
		// Avoid getting zero or less than zero as an input for post duplication.
		if ( count < 1 ) {
			$(this).val( 1 );
		} else if ( count > 5 ) {
			alert("Can not duplicate more than 5 in a single operation");
			$(this).val( 5 );
		}
		// Get the input value again in-case it is changed by above validation process.
		count	= $(this).val();
		var id		 = $(this).attr( 'id' ),
			idn		 = id.replace( 'duplicate_ppmc_item_no','' ),
			adminLink= 'admin.php?action=duplicate_ppmc_post_as_draft&copies=',
			btlink	 = adminLink+count+'&post='+idn;
		$("body").append( "<!--	Post ID: "+idn+" link: "+ btlink	+"!-->" );
		$( 'a.'+id ).attr( 'href',btlink );
	});
} // end of ready(function(){}).

); // end of jQuery document.ready(){}.
