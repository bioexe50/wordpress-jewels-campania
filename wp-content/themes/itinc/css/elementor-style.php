/*========================================== Row / Colum Background Base Css ==========================================*/
.thsn-col-stretched-yes .thsn-stretched-div{
    position: absolute;
    height: 100%;
    width: 100%;
    top:0;
    left: 0;    
    width: auto;
    z-index: 1;
    overflow: hidden;
}
.thsn-col-stretched-right .thsn-stretched-div,
.thsn-col-stretched-left .thsn-stretched-div{
    right: 0;
}
.elementor-section.elementor-top-section.thsn-bg-image-over-color.thsn-bgimage-yes:before,
.elementor-column.elementor-top-column.thsn-bgimage-yes.thsn-bg-image-over-color > .thsn-stretched-div:before,

.elementor-column.elementor-top-column.thsn-bg-image-over-color > .elementor-widget-wrap:before,
.elementor-column.elementor-top-column.thsn-bg-image-over-color > .elementor-column-wrap:before{ 
	background-color: transparent !important;
}
.elementor-column.thsn-col-stretched-yes.thsn-bgimage-yes{
    background-image: none;
    background-color: transparent;
}
.thsn-bgimage-over-bgcolor.thsn-bgimage-yes .thsn-stretched-div:before,
.thsn-bgimage-over-bgcolor.thsn-bgimage-yes:before{
   background-color: transparent !important
}


.elementor-top-section:before, 
.thsn-col-stretched-yes .thsn-stretched-div:before,

.elementor-column.elementor-top-column .elementor-widget-wrap:before,
.elementor-column.elementor-top-column .elementor-column-wrap:before,

.elementor-inner-column > div:before,
.elementor-inner-section:before{
	position: absolute;
	height: 100%;
	width: 100%;
	top: 0;
	left: 0;
	content: "";
	display: block;
	z-index: 1;
}


/* --------------------------------------
 * Row Colum - Global BG Color
 * ---------------------------------------*/


/*--- Main RoW BG ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-globalcolor, 
.elementor-section.elementor-top-section.thsn-elementor-bg-color-globalcolor:before, 
.elementor-section.elementor-inner-section.thsn-elementor-bg-color-globalcolor {
    background-color: <?php echo esc_attr($global_color); ?>;
}

/*--- Main Row BG - with image ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-globalcolor.thsn-bgimage-yes:before{
	background-color: <?php echo thsn_hex2rgb($global_color, '0.90') ?>;
}



/*--- Main Colum BG - ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor:not(.thsn-bgimage-yes) .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bg-image-over-color .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor:not(.thsn-col-stretched-yes) > .elementor-widget-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-widget-wrap,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor:not(.thsn-bgimage-yes) .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bg-image-over-color .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor:not(.thsn-col-stretched-yes) > .elementor-column-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-column-wrap{
	background-color: <?php echo esc_attr($global_color); ?> !important;
}


/*--- Main Colum BG - with image ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-widget-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-widget-wrap .thsn-stretched-div:before,
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor .elementor-widget-wrap .thsn-bgimage-yes.thsn-stretched-div:before,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-column-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-column-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-globalcolor .elementor-column-wrap .thsn-bgimage-yes.thsn-stretched-div:before{
	background-color: <?php echo thsn_hex2rgb($global_color, '0.90') ?>;
}


/*--- Inner Colum BG  ---*/
.elementor-inner-section.thsn-elementor-bg-color-globalcolor{ 
	background-color: <?php echo esc_attr($global_color); ?> !important;
}

/*--- Inner Row - without image ---*/
.elementor-inner-section.thsn-elementor-bg-color-globalcolor:not(.thsn-bg-image-over-color):before{
	background-color: <?php echo thsn_hex2rgb($global_color, '0.90') ?>;
}


/*--- Inner Colum BG ---*/
.elementor-inner-column.thsn-elementor-bg-color-globalcolor > div.elementor-column-wrap,
.elementor-inner-column.thsn-elementor-bg-color-globalcolor > div.elementor-widget-wrap{ 
	background-color: <?php echo esc_attr($global_color); ?> !important;
}

/*--- Inner Colum BG - with image ---*/
.elementor-inner-column.thsn-elementor-bg-color-globalcolor:not(.thsn-bg-image-over-color) > div.elementor-column-wrap:before,
.elementor-inner-column.thsn-elementor-bg-color-globalcolor:not(.thsn-bg-image-over-color) > div.elementor-widget-wrap:before{
	background-color: <?php echo thsn_hex2rgb($global_color, '0.90') ?>;
}


/*====== End --- Row Colum - Global BG Color ======*/

/* --------------------------------------
 * Row Colum - Light BG Color
 * ---------------------------------------*/


/*--- Main RoW BG ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-light, 
.elementor-section.elementor-top-section.thsn-elementor-bg-color-light:before, 
.elementor-section.elementor-inner-section.thsn-elementor-bg-color-light {
    background-color: <?php echo esc_attr($light_bg_color); ?>;
}

/*--- Main Row BG - with image ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-light.thsn-bgimage-yes:before{
	background-color: <?php echo thsn_hex2rgb($light_bg_color, '0.90') ?>;
}

/*--- Main Colum BG - ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light:not(.thsn-bgimage-yes) .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bg-image-over-color .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light:not(.thsn-col-stretched-yes) > .elementor-widget-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-widget-wrap,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-light:not(.thsn-bgimage-yes) .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bg-image-over-color .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light:not(.thsn-col-stretched-yes) > .elementor-column-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-column-wrap{
	background-color: <?php echo esc_attr($light_bg_color); ?> !important;
}


/*--- Main Colum BG - with image ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-widget-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-widget-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light .elementor-widget-wrap .thsn-bgimage-yes.thsn-stretched-div:before,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-column-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-column-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-light .elementor-column-wrap .thsn-bgimage-yes.thsn-stretched-div:before{
	background-color: <?php echo thsn_hex2rgb($light_bg_color, '0.90') ?>;
}


/*--- Inner Colum BG  ---*/
.elementor-inner-section.thsn-elementor-bg-color-light{ 
	background-color: <?php echo esc_attr($light_bg_color); ?> !important;
}

/*--- Inner Row - without image ---*/
.elementor-inner-section.thsn-elementor-bg-color-light:not(.thsn-bg-image-over-color):before{
	background-color: <?php echo thsn_hex2rgb($light_bg_color, '0.90') ?>;
}


/*--- Inner Colum BG ---*/
.elementor-inner-column.thsn-elementor-bg-color-light > div.elementor-column-wrap,
.elementor-inner-column.thsn-elementor-bg-color-light > div.elementor-widget-wrap{ 
	background-color: <?php echo esc_attr($light_bg_color); ?> !important;
}

/*--- Inner Colum BG - with image ---*/
.elementor-inner-column.thsn-elementor-bg-color-light:not(.thsn-bg-image-over-color) > div.elementor-column-wrap:before,
.elementor-inner-column.thsn-elementor-bg-color-light:not(.thsn-bg-image-over-color) > div.elementor-widget-wrap:before{
	background-color: <?php echo thsn_hex2rgb($light_bg_color, '0.90') ?>;
}


/*====== End --- Row Colum - Light BG Color ======*/

/* --------------------------------------
 * Row Colum - Secondary BG Color
 * ---------------------------------------*/


/*--- Main RoW BG ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-secondary, 
.elementor-section.elementor-top-section.thsn-elementor-bg-color-secondary:before, 
.elementor-section.elementor-inner-section.thsn-elementor-bg-color-secondary {
    background-color: <?php echo esc_attr($secondary_color); ?>;
}

/*--- Main Row BG - with image ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-secondary.thsn-bgimage-yes:before{
	background-color: <?php echo thsn_hex2rgb($secondary_color, '0.90') ?>;
}

/*--- Main Colum BG - ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary:not(.thsn-bgimage-yes) .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bg-image-over-color .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary:not(.thsn-col-stretched-yes) > .elementor-widget-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-widget-wrap,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary:not(.thsn-bgimage-yes) .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bg-image-over-color .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary:not(.thsn-col-stretched-yes) > .elementor-column-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-column-wrap{
	background-color: <?php echo esc_attr($secondary_color); ?> !important;
}


/*--- Main Colum BG - with image ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-widget-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-widget-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary .elementor-widget-wrap .thsn-bgimage-yes.thsn-stretched-div:before,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-column-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-column-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-secondary .elementor-column-wrap .thsn-bgimage-yes.thsn-stretched-div:before{
	background-color: <?php echo thsn_hex2rgb($secondary_color, '0.90') ?>;
}


/*--- Inner Colum BG  ---*/
.elementor-inner-section.thsn-elementor-bg-color-secondary{ 
	background-color: <?php echo esc_attr($secondary_color); ?> !important;
}

/*--- Inner Row - without image ---*/
.elementor-inner-section.thsn-elementor-bg-color-secondary:not(.thsn-bg-image-over-color):before{
	background-color: <?php echo thsn_hex2rgb($secondary_color, '0.90') ?>;
}


/*--- Inner Colum BG ---*/
.elementor-inner-column.thsn-elementor-bg-color-secondary > div.elementor-column-wrap,
.elementor-inner-column.thsn-elementor-bg-color-secondary > div.elementor-widget-wrap{ 
	background-color: <?php echo esc_attr($secondary_color); ?> !important;
}

/*--- Inner Colum BG - with image ---*/
.elementor-inner-column.thsn-elementor-bg-color-secondary:not(.thsn-bg-image-over-color) > div.elementor-column-wrap:before,
.elementor-inner-column.thsn-elementor-bg-color-secondary:not(.thsn-bg-image-over-color) > div.elementor-widget-wrap:before{
	background-color: <?php echo thsn_hex2rgb($secondary_color, '0.90') ?>;
}



/*====== End --- Row Colum - Secondary BG Color ======*/

/* --------------------------------------
 * Row Colum - Blackish BG Color
 * ---------------------------------------*/


/*--- Main RoW BG ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-blackish, 
.elementor-section.elementor-top-section.thsn-elementor-bg-color-blackish:before, 
.elementor-section.elementor-inner-section.thsn-elementor-bg-color-blackish {
    background-color: <?php echo esc_attr($blackish_color); ?>;
}

/*--- Main Row BG - with image ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-blackish.thsn-bgimage-yes:before{
	background-color: <?php echo thsn_hex2rgb($blackish_color, '0.90') ?>;
}

/*--- Main Colum BG - ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish:not(.thsn-bgimage-yes) .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bg-image-over-color .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish:not(.thsn-col-stretched-yes) > .elementor-widget-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-widget-wrap,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish:not(.thsn-bgimage-yes) .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bg-image-over-color .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish:not(.thsn-col-stretched-yes) > .elementor-column-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-column-wrap{
	background-color: <?php echo esc_attr($blackish_color); ?> !important;
}


/*--- Main Colum BG - with image ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-widget-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-widget-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish .elementor-widget-wrap .thsn-bgimage-yes.thsn-stretched-div:before,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-column-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-column-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-blackish .elementor-column-wrap .thsn-bgimage-yes.thsn-stretched-div:before{
	background-color: <?php echo thsn_hex2rgb($blackish_color, '0.90') ?>;
}


/*--- Inner Colum BG  ---*/
.elementor-inner-section.thsn-elementor-bg-color-blackish{ 
	background-color: <?php echo esc_attr($blackish_color); ?> !important;
}

/*--- Inner Row - without image ---*/
.elementor-inner-section.thsn-elementor-bg-color-blackish:not(.thsn-bg-image-over-color):before{
	background-color: <?php echo thsn_hex2rgb($blackish_color, '0.90') ?>;
}


/*--- Inner Colum BG ---*/
.elementor-inner-column.thsn-elementor-bg-color-blackish > div.elementor-column-wrap,
.elementor-inner-column.thsn-elementor-bg-color-blackish > div.elementor-widget-wrap{ 
	background-color: <?php echo esc_attr($blackish_color); ?> !important;
}

/*--- Inner Colum BG - with image ---*/
.elementor-inner-column.thsn-elementor-bg-color-blackish:not(.thsn-bg-image-over-color) > div.elementor-column-wrap:before,
.elementor-inner-column.thsn-elementor-bg-color-blackish:not(.thsn-bg-image-over-color) > div.elementor-widget-wrap:before{
	background-color: <?php echo thsn_hex2rgb($blackish_color, '0.90') ?>;
}


/*====== End --- Row Colum - Blackish BG Color ======*/

/* --------------------------------------
 * Row Colum - White BG Color
 * ---------------------------------------*/



/*--- Main RoW BG ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-white, 
.elementor-section.elementor-top-section.thsn-elementor-bg-color-white:before, 
.elementor-section.elementor-inner-section.thsn-elementor-bg-color-white {
    background-color: <?php echo esc_attr($white_color); ?>;
}

/*--- Main Row BG - with image ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-white.thsn-bgimage-yes:before{
	background-color: <?php echo thsn_hex2rgb($white_color, '0.90') ?>;
}

/*--- Main Colum BG - ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white:not(.thsn-bgimage-yes) .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bg-image-over-color .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white:not(.thsn-col-stretched-yes) > .elementor-widget-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-widget-wrap,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-white:not(.thsn-bgimage-yes) .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bg-image-over-color .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white:not(.thsn-col-stretched-yes) > .elementor-column-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-column-wrap{
	background-color: <?php echo esc_attr($white_color); ?> !important;
}


/*--- Main Colum BG - with image ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-widget-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-widget-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white .elementor-widget-wrap .thsn-bgimage-yes.thsn-stretched-div:before,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-column-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-column-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-white .elementor-column-wrap .thsn-bgimage-yes.thsn-stretched-div:before{
	background-color: <?php echo thsn_hex2rgb($white_color, '0.90') ?>;
}


/*--- Inner Colum BG  ---*/
.elementor-inner-section.thsn-elementor-bg-color-white{ 
	background-color: <?php echo esc_attr($white_color); ?> !important;
}

/*--- Inner Row - without image ---*/
.elementor-inner-section.thsn-elementor-bg-color-white:not(.thsn-bg-image-over-color):before{
	background-color: <?php echo thsn_hex2rgb($white_color, '0.90') ?>;
}


/*--- Inner Colum BG ---*/
.elementor-inner-column.thsn-elementor-bg-color-white > div.elementor-column-wrap,
.elementor-inner-column.thsn-elementor-bg-color-white > div.elementor-widget-wrap{ 
	background-color: <?php echo esc_attr($white_color); ?> !important;
}

/*--- Inner Colum BG - with image ---*/
.elementor-inner-column.thsn-elementor-bg-color-white:not(.thsn-bg-image-over-color) > div.elementor-column-wrap:before,
.elementor-inner-column.thsn-elementor-bg-color-white:not(.thsn-bg-image-over-color) > div.elementor-widget-wrap:before{
	background-color: <?php echo thsn_hex2rgb($white_color, '0.90') ?>;
}


/*====== End --- Row Colum - White BG Color ======*/


/* --------------------------------------
 * Row Colum - Gradient BG Color
 * ---------------------------------------*/

/*--- Main RoW BG ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-gradient, 
.elementor-section.elementor-top-section.thsn-elementor-bg-color-gradient:before, 
.elementor-section.elementor-inner-section.thsn-elementor-bg-color-gradient {
    background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
}

/*--- Main Row BG - with image ---*/
.elementor-section.elementor-top-section.thsn-elementor-bg-color-gradient.thsn-bgimage-yes:before{
    background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
    opacity: 0.5;
}

/*--- Main Colum BG - ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient:not(.thsn-bgimage-yes) .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bg-image-over-color .elementor-widget-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient:not(.thsn-col-stretched-yes) > .elementor-widget-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-widget-wrap,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient:not(.thsn-bgimage-yes) .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bg-image-over-color .elementor-column-wrap > .thsn-stretched-div, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient:not(.thsn-col-stretched-yes) > .elementor-column-wrap, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bg-image-over-color:not(.thsn-col-stretched-yes) > .elementor-column-wrap{
    background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
}


/*--- Main Colum BG - with image ---*/
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-widget-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-widget-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient .elementor-widget-wrap .thsn-bgimage-yes.thsn-stretched-div:before,

.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bgimage-yes:not(.thsn-col-stretched-yes) > .elementor-column-wrap:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient.thsn-bgimage-yes:not(.thsn-bg-image-over-color) .elementor-column-wrap .thsn-stretched-div:before, 
.elementor-column.elementor-top-column.thsn-elementor-bg-color-gradient .elementor-column-wrap .thsn-bgimage-yes.thsn-stretched-div:before{
    background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 80%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
    opacity: 0.5;
}


/*--- Inner Colum BG  ---*/
.elementor-inner-section.thsn-elementor-bg-color-gradient{ 
    background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
}

/*--- Inner Row - without image ---*/
.elementor-inner-section.thsn-elementor-bg-color-gradient:not(.thsn-bg-image-over-color):before{
    background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
    opacity: 0.5;
}


/*--- Inner Colum BG ---*/
.elementor-inner-column.thsn-elementor-bg-color-gradient > div.elementor-column-wrap,
.elementor-inner-column.thsn-elementor-bg-color-gradient > div.elementor-widget-wrap{ 
	background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
}

/*--- Inner Colum BG - with image ---*/
.elementor-inner-column.thsn-elementor-bg-color-gradient:not(.thsn-bg-image-over-color) > div.elementor-column-wrap:before,
.elementor-inner-column.thsn-elementor-bg-color-gradient:not(.thsn-bg-image-over-color) > div.elementor-widget-wrap:before{
    background-image: -ms-linear-gradient(right, <?php echo esc_attr($gradient_first); ?> 0%, <?php echo esc_attr($gradient_last); ?> 100%);
	background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
}


/*====== End --- Row Colum - Gradient BG Color ======*/

























