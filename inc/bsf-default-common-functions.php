<?php

/*
 * Default functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'get_bsf_currencies' ) ) {

	/**
	 * Get full list of currency codes.
	 */
	function get_bsf_currencies() {
		static $currencies ;

		if ( ! isset( $currencies ) ) {
			$currencies = array_unique(
					apply_filters(
							'bsf_currencies' , array(
				'AED' => __( 'United Arab Emirates dirham' , 'zovonto' ) ,
				'AFN' => __( 'Afghan afghani' , 'zovonto' ) ,
				'ALL' => __( 'Albanian lek' , 'zovonto' ) ,
				'AMD' => __( 'Armenian dram' , 'zovonto' ) ,
				'ANG' => __( 'Netherlands Antillean guilder' , 'zovonto' ) ,
				'AOA' => __( 'Angolan kwanza' , 'zovonto' ) ,
				'ARS' => __( 'Argentine peso' , 'zovonto' ) ,
				'AUD' => __( 'Australian dollar' , 'zovonto' ) ,
				'AWG' => __( 'Aruban florin' , 'zovonto' ) ,
				'AZN' => __( 'Azerbaijani manat' , 'zovonto' ) ,
				'BAM' => __( 'Bosnia and Herzegovina convertible mark' , 'zovonto' ) ,
				'BBD' => __( 'Barbadian dollar' , 'zovonto' ) ,
				'BDT' => __( 'Bangladeshi taka' , 'zovonto' ) ,
				'BGN' => __( 'Bulgarian lev' , 'zovonto' ) ,
				'BHD' => __( 'Bahraini dinar' , 'zovonto' ) ,
				'BIF' => __( 'Burundian franc' , 'zovonto' ) ,
				'BMD' => __( 'Bermudian dollar' , 'zovonto' ) ,
				'BND' => __( 'Brunei dollar' , 'zovonto' ) ,
				'BOB' => __( 'Bolivian boliviano' , 'zovonto' ) ,
				'BRL' => __( 'Brazilian real' , 'zovonto' ) ,
				'BSD' => __( 'Bahamian dollar' , 'zovonto' ) ,
				'BTC' => __( 'Bitcoin' , 'zovonto' ) ,
				'BTN' => __( 'Bhutanese ngultrum' , 'zovonto' ) ,
				'BWP' => __( 'Botswana pula' , 'zovonto' ) ,
				'BYR' => __( 'Belarusian ruble (old)' , 'zovonto' ) ,
				'BYN' => __( 'Belarusian ruble' , 'zovonto' ) ,
				'BZD' => __( 'Belize dollar' , 'zovonto' ) ,
				'CAD' => __( 'Canadian dollar' , 'zovonto' ) ,
				'CDF' => __( 'Congolese franc' , 'zovonto' ) ,
				'CHF' => __( 'Swiss franc' , 'zovonto' ) ,
				'CLP' => __( 'Chilean peso' , 'zovonto' ) ,
				'CNY' => __( 'Chinese yuan' , 'zovonto' ) ,
				'COP' => __( 'Colombian peso' , 'zovonto' ) ,
				'CRC' => __( 'Costa Rican col&oacute;n' , 'zovonto' ) ,
				'CUC' => __( 'Cuban convertible peso' , 'zovonto' ) ,
				'CUP' => __( 'Cuban peso' , 'zovonto' ) ,
				'CVE' => __( 'Cape Verdean escudo' , 'zovonto' ) ,
				'CZK' => __( 'Czech koruna' , 'zovonto' ) ,
				'DJF' => __( 'Djiboutian franc' , 'zovonto' ) ,
				'DKK' => __( 'Danish krone' , 'zovonto' ) ,
				'DOP' => __( 'Dominican peso' , 'zovonto' ) ,
				'DZD' => __( 'Algerian dinar' , 'zovonto' ) ,
				'EGP' => __( 'Egyptian pound' , 'zovonto' ) ,
				'ERN' => __( 'Eritrean nakfa' , 'zovonto' ) ,
				'ETB' => __( 'Ethiopian birr' , 'zovonto' ) ,
				'EUR' => __( 'Euro' , 'zovonto' ) ,
				'FJD' => __( 'Fijian dollar' , 'zovonto' ) ,
				'FKP' => __( 'Falkland Islands pound' , 'zovonto' ) ,
				'GBP' => __( 'Pound sterling' , 'zovonto' ) ,
				'GEL' => __( 'Georgian lari' , 'zovonto' ) ,
				'GGP' => __( 'Guernsey pound' , 'zovonto' ) ,
				'GHS' => __( 'Ghana cedi' , 'zovonto' ) ,
				'GIP' => __( 'Gibraltar pound' , 'zovonto' ) ,
				'GMD' => __( 'Gambian dalasi' , 'zovonto' ) ,
				'GNF' => __( 'Guinean franc' , 'zovonto' ) ,
				'GTQ' => __( 'Guatemalan quetzal' , 'zovonto' ) ,
				'GYD' => __( 'Guyanese dollar' , 'zovonto' ) ,
				'HKD' => __( 'Hong Kong dollar' , 'zovonto' ) ,
				'HNL' => __( 'Honduran lempira' , 'zovonto' ) ,
				'HRK' => __( 'Croatian kuna' , 'zovonto' ) ,
				'HTG' => __( 'Haitian gourde' , 'zovonto' ) ,
				'HUF' => __( 'Hungarian forint' , 'zovonto' ) ,
				'IDR' => __( 'Indonesian rupiah' , 'zovonto' ) ,
				'ILS' => __( 'Israeli new shekel' , 'zovonto' ) ,
				'IMP' => __( 'Manx pound' , 'zovonto' ) ,
				'INR' => __( 'Indian rupee' , 'zovonto' ) ,
				'IQD' => __( 'Iraqi dinar' , 'zovonto' ) ,
				'IRR' => __( 'Iranian rial' , 'zovonto' ) ,
				'IRT' => __( 'Iranian toman' , 'zovonto' ) ,
				'ISK' => __( 'Icelandic kr&oacute;na' , 'zovonto' ) ,
				'JEP' => __( 'Jersey pound' , 'zovonto' ) ,
				'JMD' => __( 'Jamaican dollar' , 'zovonto' ) ,
				'JOD' => __( 'Jordanian dinar' , 'zovonto' ) ,
				'JPY' => __( 'Japanese yen' , 'zovonto' ) ,
				'KES' => __( 'Kenyan shilling' , 'zovonto' ) ,
				'KGS' => __( 'Kyrgyzstani som' , 'zovonto' ) ,
				'KHR' => __( 'Cambodian riel' , 'zovonto' ) ,
				'KMF' => __( 'Comorian franc' , 'zovonto' ) ,
				'KPW' => __( 'North Korean won' , 'zovonto' ) ,
				'KRW' => __( 'South Korean won' , 'zovonto' ) ,
				'KWD' => __( 'Kuwaiti dinar' , 'zovonto' ) ,
				'KYD' => __( 'Cayman Islands dollar' , 'zovonto' ) ,
				'KZT' => __( 'Kazakhstani tenge' , 'zovonto' ) ,
				'LAK' => __( 'Lao kip' , 'zovonto' ) ,
				'LBP' => __( 'Lebanese pound' , 'zovonto' ) ,
				'LKR' => __( 'Sri Lankan rupee' , 'zovonto' ) ,
				'LRD' => __( 'Liberian dollar' , 'zovonto' ) ,
				'LSL' => __( 'Lesotho loti' , 'zovonto' ) ,
				'LYD' => __( 'Libyan dinar' , 'zovonto' ) ,
				'MAD' => __( 'Moroccan dirham' , 'zovonto' ) ,
				'MDL' => __( 'Moldovan leu' , 'zovonto' ) ,
				'MGA' => __( 'Malagasy ariary' , 'zovonto' ) ,
				'MKD' => __( 'Macedonian denar' , 'zovonto' ) ,
				'MMK' => __( 'Burmese kyat' , 'zovonto' ) ,
				'MNT' => __( 'Mongolian t&ouml;gr&ouml;g' , 'zovonto' ) ,
				'MOP' => __( 'Macanese pataca' , 'zovonto' ) ,
				'MRO' => __( 'Mauritanian ouguiya' , 'zovonto' ) ,
				'MUR' => __( 'Mauritian rupee' , 'zovonto' ) ,
				'MVR' => __( 'Maldivian rufiyaa' , 'zovonto' ) ,
				'MWK' => __( 'Malawian kwacha' , 'zovonto' ) ,
				'MXN' => __( 'Mexican peso' , 'zovonto' ) ,
				'MYR' => __( 'Malaysian ringgit' , 'zovonto' ) ,
				'MZN' => __( 'Mozambican metical' , 'zovonto' ) ,
				'NAD' => __( 'Namibian dollar' , 'zovonto' ) ,
				'NGN' => __( 'Nigerian naira' , 'zovonto' ) ,
				'NIO' => __( 'Nicaraguan c&oacute;rdoba' , 'zovonto' ) ,
				'NOK' => __( 'Norwegian krone' , 'zovonto' ) ,
				'NPR' => __( 'Nepalese rupee' , 'zovonto' ) ,
				'NZD' => __( 'New Zealand dollar' , 'zovonto' ) ,
				'OMR' => __( 'Omani rial' , 'zovonto' ) ,
				'PAB' => __( 'Panamanian balboa' , 'zovonto' ) ,
				'PEN' => __( 'Peruvian nuevo sol' , 'zovonto' ) ,
				'PGK' => __( 'Papua New Guinean kina' , 'zovonto' ) ,
				'PHP' => __( 'Philippine peso' , 'zovonto' ) ,
				'PKR' => __( 'Pakistani rupee' , 'zovonto' ) ,
				'PLN' => __( 'Polish z&#x142;oty' , 'zovonto' ) ,
				'PRB' => __( 'Transnistrian ruble' , 'zovonto' ) ,
				'PYG' => __( 'Paraguayan guaran&iacute;' , 'zovonto' ) ,
				'QAR' => __( 'Qatari riyal' , 'zovonto' ) ,
				'RON' => __( 'Romanian leu' , 'zovonto' ) ,
				'RSD' => __( 'Serbian dinar' , 'zovonto' ) ,
				'RUB' => __( 'Russian ruble' , 'zovonto' ) ,
				'RWF' => __( 'Rwandan franc' , 'zovonto' ) ,
				'SAR' => __( 'Saudi riyal' , 'zovonto' ) ,
				'SBD' => __( 'Solomon Islands dollar' , 'zovonto' ) ,
				'SCR' => __( 'Seychellois rupee' , 'zovonto' ) ,
				'SDG' => __( 'Sudanese pound' , 'zovonto' ) ,
				'SEK' => __( 'Swedish krona' , 'zovonto' ) ,
				'SGD' => __( 'Singapore dollar' , 'zovonto' ) ,
				'SHP' => __( 'Saint Helena pound' , 'zovonto' ) ,
				'SLL' => __( 'Sierra Leonean leone' , 'zovonto' ) ,
				'SOS' => __( 'Somali shilling' , 'zovonto' ) ,
				'SRD' => __( 'Surinamese dollar' , 'zovonto' ) ,
				'SSP' => __( 'South Sudanese pound' , 'zovonto' ) ,
				'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra' , 'zovonto' ) ,
				'SYP' => __( 'Syrian pound' , 'zovonto' ) ,
				'SZL' => __( 'Swazi lilangeni' , 'zovonto' ) ,
				'THB' => __( 'Thai baht' , 'zovonto' ) ,
				'TJS' => __( 'Tajikistani somoni' , 'zovonto' ) ,
				'TMT' => __( 'Turkmenistan manat' , 'zovonto' ) ,
				'TND' => __( 'Tunisian dinar' , 'zovonto' ) ,
				'TOP' => __( 'Tongan pa&#x2bb;anga' , 'zovonto' ) ,
				'TRY' => __( 'Turkish lira' , 'zovonto' ) ,
				'TTD' => __( 'Trinidad and Tobago dollar' , 'zovonto' ) ,
				'TWD' => __( 'New Taiwan dollar' , 'zovonto' ) ,
				'TZS' => __( 'Tanzanian shilling' , 'zovonto' ) ,
				'UAH' => __( 'Ukrainian hryvnia' , 'zovonto' ) ,
				'UGX' => __( 'Ugandan shilling' , 'zovonto' ) ,
				'USD' => __( 'United States (US) dollar' , 'zovonto' ) ,
				'UYU' => __( 'Uruguayan peso' , 'zovonto' ) ,
				'UZS' => __( 'Uzbekistani som' , 'zovonto' ) ,
				'VEF' => __( 'Venezuelan bol&iacute;var' , 'zovonto' ) ,
				'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng' , 'zovonto' ) ,
				'VUV' => __( 'Vanuatu vatu' , 'zovonto' ) ,
				'WST' => __( 'Samoan t&#x101;l&#x101;' , 'zovonto' ) ,
				'XAF' => __( 'Central African CFA franc' , 'zovonto' ) ,
				'XCD' => __( 'East Caribbean dollar' , 'zovonto' ) ,
				'XOF' => __( 'West African CFA franc' , 'zovonto' ) ,
				'XPF' => __( 'CFP franc' , 'zovonto' ) ,
				'YER' => __( 'Yemeni rial' , 'zovonto' ) ,
				'ZAR' => __( 'South African rand' , 'zovonto' ) ,
				'ZMW' => __( 'Zambian kwacha' , 'zovonto' ) ,
							)
					)
					) ;
		}

		return $currencies ;
	}

}

if ( ! function_exists( 'get_bsf_currency' ) ) {

	/**
	 * Get site currency.
	 */
	function get_bsf_currency() {
		$currency = get_option( 'bsf_currency' , 'USD' ) ;

		return apply_filters( 'bsf_get_curreny' , $currency ) ;
	}

}

if ( ! function_exists( 'get_bsf_currency_symbol' ) ) {

	function get_bsf_currency_symbol( $currency = '' ) {
		if ( ! $currency ) {
			$currency = get_bsf_currency() ;
		}

		$symbols         = apply_filters(
				'bsf_currency_symbols' , array(
			'AED' => '&#x62f;.&#x625;' ,
			'AFN' => '&#x60b;' ,
			'ALL' => 'L' ,
			'AMD' => 'AMD' ,
			'ANG' => '&fnof;' ,
			'AOA' => 'Kz' ,
			'ARS' => '&#36;' ,
			'AUD' => '&#36;' ,
			'AWG' => 'Afl.' ,
			'AZN' => 'AZN' ,
			'BAM' => 'KM' ,
			'BBD' => '&#36;' ,
			'BDT' => '&#2547;&nbsp;' ,
			'BGN' => '&#1083;&#1074;.' ,
			'BHD' => '.&#x62f;.&#x628;' ,
			'BIF' => 'Fr' ,
			'BMD' => '&#36;' ,
			'BND' => '&#36;' ,
			'BOB' => 'Bs.' ,
			'BRL' => '&#82;&#36;' ,
			'BSD' => '&#36;' ,
			'BTC' => '&#3647;' ,
			'BTN' => 'Nu.' ,
			'BWP' => 'P' ,
			'BYR' => 'Br' ,
			'BYN' => 'Br' ,
			'BZD' => '&#36;' ,
			'CAD' => '&#36;' ,
			'CDF' => 'Fr' ,
			'CHF' => '&#67;&#72;&#70;' ,
			'CLP' => '&#36;' ,
			'CNY' => '&yen;' ,
			'COP' => '&#36;' ,
			'CRC' => '&#x20a1;' ,
			'CUC' => '&#36;' ,
			'CUP' => '&#36;' ,
			'CVE' => '&#36;' ,
			'CZK' => '&#75;&#269;' ,
			'DJF' => 'Fr' ,
			'DKK' => 'DKK' ,
			'DOP' => 'RD&#36;' ,
			'DZD' => '&#x62f;.&#x62c;' ,
			'EGP' => 'EGP' ,
			'ERN' => 'Nfk' ,
			'ETB' => 'Br' ,
			'EUR' => '&euro;' ,
			'FJD' => '&#36;' ,
			'FKP' => '&pound;' ,
			'GBP' => '&pound;' ,
			'GEL' => '&#x20be;' ,
			'GGP' => '&pound;' ,
			'GHS' => '&#x20b5;' ,
			'GIP' => '&pound;' ,
			'GMD' => 'D' ,
			'GNF' => 'Fr' ,
			'GTQ' => 'Q' ,
			'GYD' => '&#36;' ,
			'HKD' => '&#36;' ,
			'HNL' => 'L' ,
			'HRK' => 'Kn' ,
			'HTG' => 'G' ,
			'HUF' => '&#70;&#116;' ,
			'IDR' => 'Rp' ,
			'ILS' => '&#8362;' ,
			'IMP' => '&pound;' ,
			'INR' => '&#8377;' ,
			'IQD' => '&#x639;.&#x62f;' ,
			'IRR' => '&#xfdfc;' ,
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;' ,
			'ISK' => 'kr.' ,
			'JEP' => '&pound;' ,
			'JMD' => '&#36;' ,
			'JOD' => '&#x62f;.&#x627;' ,
			'JPY' => '&yen;' ,
			'KES' => 'KSh' ,
			'KGS' => '&#x441;&#x43e;&#x43c;' ,
			'KHR' => '&#x17db;' ,
			'KMF' => 'Fr' ,
			'KPW' => '&#x20a9;' ,
			'KRW' => '&#8361;' ,
			'KWD' => '&#x62f;.&#x643;' ,
			'KYD' => '&#36;' ,
			'KZT' => 'KZT' ,
			'LAK' => '&#8365;' ,
			'LBP' => '&#x644;.&#x644;' ,
			'LKR' => '&#xdbb;&#xdd4;' ,
			'LRD' => '&#36;' ,
			'LSL' => 'L' ,
			'LYD' => '&#x644;.&#x62f;' ,
			'MAD' => '&#x62f;.&#x645;.' ,
			'MDL' => 'MDL' ,
			'MGA' => 'Ar' ,
			'MKD' => '&#x434;&#x435;&#x43d;' ,
			'MMK' => 'Ks' ,
			'MNT' => '&#x20ae;' ,
			'MOP' => 'P' ,
			'MRO' => 'UM' ,
			'MUR' => '&#x20a8;' ,
			'MVR' => '.&#x783;' ,
			'MWK' => 'MK' ,
			'MXN' => '&#36;' ,
			'MYR' => '&#82;&#77;' ,
			'MZN' => 'MT' ,
			'NAD' => '&#36;' ,
			'NGN' => '&#8358;' ,
			'NIO' => 'C&#36;' ,
			'NOK' => '&#107;&#114;' ,
			'NPR' => '&#8360;' ,
			'NZD' => '&#36;' ,
			'OMR' => '&#x631;.&#x639;.' ,
			'PAB' => 'B/.' ,
			'PEN' => 'S/.' ,
			'PGK' => 'K' ,
			'PHP' => '&#8369;' ,
			'PKR' => '&#8360;' ,
			'PLN' => '&#122;&#322;' ,
			'PRB' => '&#x440;.' ,
			'PYG' => '&#8370;' ,
			'QAR' => '&#x631;.&#x642;' ,
			'RMB' => '&yen;' ,
			'RON' => 'lei' ,
			'RSD' => '&#x434;&#x438;&#x43d;.' ,
			'RUB' => '&#8381;' ,
			'RWF' => 'Fr' ,
			'SAR' => '&#x631;.&#x633;' ,
			'SBD' => '&#36;' ,
			'SCR' => '&#x20a8;' ,
			'SDG' => '&#x62c;.&#x633;.' ,
			'SEK' => '&#107;&#114;' ,
			'SGD' => '&#36;' ,
			'SHP' => '&pound;' ,
			'SLL' => 'Le' ,
			'SOS' => 'Sh' ,
			'SRD' => '&#36;' ,
			'SSP' => '&pound;' ,
			'STD' => 'Db' ,
			'SYP' => '&#x644;.&#x633;' ,
			'SZL' => 'L' ,
			'THB' => '&#3647;' ,
			'TJS' => '&#x405;&#x41c;' ,
			'TMT' => 'm' ,
			'TND' => '&#x62f;.&#x62a;' ,
			'TOP' => 'T&#36;' ,
			'TRY' => '&#8378;' ,
			'TTD' => '&#36;' ,
			'TWD' => '&#78;&#84;&#36;' ,
			'TZS' => 'Sh' ,
			'UAH' => '&#8372;' ,
			'UGX' => 'UGX' ,
			'USD' => '&#36;' ,
			'UYU' => '&#36;' ,
			'UZS' => 'UZS' ,
			'VEF' => 'Bs F' ,
			'VND' => '&#8363;' ,
			'VUV' => 'Vt' ,
			'WST' => 'T' ,
			'XAF' => 'CFA' ,
			'XCD' => '&#36;' ,
			'XOF' => 'CFA' ,
			'XPF' => 'Fr' ,
			'YER' => '&#xfdfc;' ,
			'ZAR' => '&#82;' ,
			'ZMW' => 'ZK' ,
				)
				) ;
		$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '' ;

		return apply_filters( 'bsf_currency_symbol' , $currency_symbol , $currency ) ;
	}

}


if ( ! function_exists( 'get_bsf_countries' ) ) {

	/**
	 * Get full list of Countries.
	 */
	function get_bsf_countries() {
		static $countries ;

		if ( ! isset( $countries ) ) {
			$countries = array_unique(
					apply_filters(
							'bsf_countries' , array(
				'AF' => __( 'Afghanistan' , 'zovonto' ) ,
				'AX' => __( '&#197;land Islands' , 'zovonto' ) ,
				'AL' => __( 'Albania' , 'zovonto' ) ,
				'DZ' => __( 'Algeria' , 'zovonto' ) ,
				'AS' => __( 'American Samoa' , 'zovonto' ) ,
				'AD' => __( 'Andorra' , 'zovonto' ) ,
				'AO' => __( 'Angola' , 'zovonto' ) ,
				'AI' => __( 'Anguilla' , 'zovonto' ) ,
				'AQ' => __( 'Antarctica' , 'zovonto' ) ,
				'AG' => __( 'Antigua and Barbuda' , 'zovonto' ) ,
				'AR' => __( 'Argentina' , 'zovonto' ) ,
				'AM' => __( 'Armenia' , 'zovonto' ) ,
				'AW' => __( 'Aruba' , 'zovonto' ) ,
				'AU' => __( 'Australia' , 'zovonto' ) ,
				'AT' => __( 'Austria' , 'zovonto' ) ,
				'AZ' => __( 'Azerbaijan' , 'zovonto' ) ,
				'BS' => __( 'Bahamas' , 'zovonto' ) ,
				'BH' => __( 'Bahrain' , 'zovonto' ) ,
				'BD' => __( 'Bangladesh' , 'zovonto' ) ,
				'BB' => __( 'Barbados' , 'zovonto' ) ,
				'BY' => __( 'Belarus' , 'zovonto' ) ,
				'BE' => __( 'Belgium' , 'zovonto' ) ,
				'PW' => __( 'Belau' , 'zovonto' ) ,
				'BZ' => __( 'Belize' , 'zovonto' ) ,
				'BJ' => __( 'Benin' , 'zovonto' ) ,
				'BM' => __( 'Bermuda' , 'zovonto' ) ,
				'BT' => __( 'Bhutan' , 'zovonto' ) ,
				'BO' => __( 'Bolivia' , 'zovonto' ) ,
				'BQ' => __( 'Bonaire, Saint Eustatius and Saba' , 'zovonto' ) ,
				'BA' => __( 'Bosnia and Herzegovina' , 'zovonto' ) ,
				'BW' => __( 'Botswana' , 'zovonto' ) ,
				'BV' => __( 'Bouvet Island' , 'zovonto' ) ,
				'BR' => __( 'Brazil' , 'zovonto' ) ,
				'IO' => __( 'British Indian Ocean Territory' , 'zovonto' ) ,
				'VG' => __( 'British Virgin Islands' , 'zovonto' ) ,
				'BN' => __( 'Brunei' , 'zovonto' ) ,
				'BG' => __( 'Bulgaria' , 'zovonto' ) ,
				'BF' => __( 'Burkina Faso' , 'zovonto' ) ,
				'BI' => __( 'Burundi' , 'zovonto' ) ,
				'KH' => __( 'Cambodia' , 'zovonto' ) ,
				'CM' => __( 'Cameroon' , 'zovonto' ) ,
				'CA' => __( 'Canada' , 'zovonto' ) ,
				'CV' => __( 'Cape Verde' , 'zovonto' ) ,
				'KY' => __( 'Cayman Islands' , 'zovonto' ) ,
				'CF' => __( 'Central African Republic' , 'zovonto' ) ,
				'TD' => __( 'Chad' , 'zovonto' ) ,
				'CL' => __( 'Chile' , 'zovonto' ) ,
				'CN' => __( 'China' , 'zovonto' ) ,
				'CX' => __( 'Christmas Island' , 'zovonto' ) ,
				'CC' => __( 'Cocos (Keeling) Islands' , 'zovonto' ) ,
				'CO' => __( 'Colombia' , 'zovonto' ) ,
				'KM' => __( 'Comoros' , 'zovonto' ) ,
				'CG' => __( 'Congo (Brazzaville)' , 'zovonto' ) ,
				'CD' => __( 'Congo (Kinshasa)' , 'zovonto' ) ,
				'CK' => __( 'Cook Islands' , 'zovonto' ) ,
				'CR' => __( 'Costa Rica' , 'zovonto' ) ,
				'HR' => __( 'Croatia' , 'zovonto' ) ,
				'CU' => __( 'Cuba' , 'zovonto' ) ,
				'CW' => __( 'Cura&ccedil;ao' , 'zovonto' ) ,
				'CY' => __( 'Cyprus' , 'zovonto' ) ,
				'CZ' => __( 'Czech Republic' , 'zovonto' ) ,
				'DK' => __( 'Denmark' , 'zovonto' ) ,
				'DJ' => __( 'Djibouti' , 'zovonto' ) ,
				'DM' => __( 'Dominica' , 'zovonto' ) ,
				'DO' => __( 'Dominican Republic' , 'zovonto' ) ,
				'EC' => __( 'Ecuador' , 'zovonto' ) ,
				'EG' => __( 'Egypt' , 'zovonto' ) ,
				'SV' => __( 'El Salvador' , 'zovonto' ) ,
				'GQ' => __( 'Equatorial Guinea' , 'zovonto' ) ,
				'ER' => __( 'Eritrea' , 'zovonto' ) ,
				'EE' => __( 'Estonia' , 'zovonto' ) ,
				'ET' => __( 'Ethiopia' , 'zovonto' ) ,
				'FK' => __( 'Falkland Islands' , 'zovonto' ) ,
				'FO' => __( 'Faroe Islands' , 'zovonto' ) ,
				'FJ' => __( 'Fiji' , 'zovonto' ) ,
				'FI' => __( 'Finland' , 'zovonto' ) ,
				'FR' => __( 'France' , 'zovonto' ) ,
				'GF' => __( 'French Guiana' , 'zovonto' ) ,
				'PF' => __( 'French Polynesia' , 'zovonto' ) ,
				'TF' => __( 'French Southern Territories' , 'zovonto' ) ,
				'GA' => __( 'Gabon' , 'zovonto' ) ,
				'GM' => __( 'Gambia' , 'zovonto' ) ,
				'GE' => __( 'Georgia' , 'zovonto' ) ,
				'DE' => __( 'Germany' , 'zovonto' ) ,
				'GH' => __( 'Ghana' , 'zovonto' ) ,
				'GI' => __( 'Gibraltar' , 'zovonto' ) ,
				'GR' => __( 'Greece' , 'zovonto' ) ,
				'GL' => __( 'Greenland' , 'zovonto' ) ,
				'GD' => __( 'Grenada' , 'zovonto' ) ,
				'GP' => __( 'Guadeloupe' , 'zovonto' ) ,
				'GU' => __( 'Guam' , 'zovonto' ) ,
				'GT' => __( 'Guatemala' , 'zovonto' ) ,
				'GG' => __( 'Guernsey' , 'zovonto' ) ,
				'GN' => __( 'Guinea' , 'zovonto' ) ,
				'GW' => __( 'Guinea-Bissau' , 'zovonto' ) ,
				'GY' => __( 'Guyana' , 'zovonto' ) ,
				'HT' => __( 'Haiti' , 'zovonto' ) ,
				'HM' => __( 'Heard Island and McDonald Islands' , 'zovonto' ) ,
				'HN' => __( 'Honduras' , 'zovonto' ) ,
				'HK' => __( 'Hong Kong' , 'zovonto' ) ,
				'HU' => __( 'Hungary' , 'zovonto' ) ,
				'IS' => __( 'Iceland' , 'zovonto' ) ,
				'IN' => __( 'India' , 'zovonto' ) ,
				'ID' => __( 'Indonesia' , 'zovonto' ) ,
				'IR' => __( 'Iran' , 'zovonto' ) ,
				'IQ' => __( 'Iraq' , 'zovonto' ) ,
				'IE' => __( 'Ireland' , 'zovonto' ) ,
				'IM' => __( 'Isle of Man' , 'zovonto' ) ,
				'IL' => __( 'Israel' , 'zovonto' ) ,
				'IT' => __( 'Italy' , 'zovonto' ) ,
				'CI' => __( 'Ivory Coast' , 'zovonto' ) ,
				'JM' => __( 'Jamaica' , 'zovonto' ) ,
				'JP' => __( 'Japan' , 'zovonto' ) ,
				'JE' => __( 'Jersey' , 'zovonto' ) ,
				'JO' => __( 'Jordan' , 'zovonto' ) ,
				'KZ' => __( 'Kazakhstan' , 'zovonto' ) ,
				'KE' => __( 'Kenya' , 'zovonto' ) ,
				'KI' => __( 'Kiribati' , 'zovonto' ) ,
				'KW' => __( 'Kuwait' , 'zovonto' ) ,
				'KG' => __( 'Kyrgyzstan' , 'zovonto' ) ,
				'LA' => __( 'Laos' , 'zovonto' ) ,
				'LV' => __( 'Latvia' , 'zovonto' ) ,
				'LB' => __( 'Lebanon' , 'zovonto' ) ,
				'LS' => __( 'Lesotho' , 'zovonto' ) ,
				'LR' => __( 'Liberia' , 'zovonto' ) ,
				'LY' => __( 'Libya' , 'zovonto' ) ,
				'LI' => __( 'Liechtenstein' , 'zovonto' ) ,
				'LT' => __( 'Lithuania' , 'zovonto' ) ,
				'LU' => __( 'Luxembourg' , 'zovonto' ) ,
				'MO' => __( 'Macao S.A.R., China' , 'zovonto' ) ,
				'MK' => __( 'Macedonia' , 'zovonto' ) ,
				'MG' => __( 'Madagascar' , 'zovonto' ) ,
				'MW' => __( 'Malawi' , 'zovonto' ) ,
				'MY' => __( 'Malaysia' , 'zovonto' ) ,
				'MV' => __( 'Maldives' , 'zovonto' ) ,
				'ML' => __( 'Mali' , 'zovonto' ) ,
				'MT' => __( 'Malta' , 'zovonto' ) ,
				'MH' => __( 'Marshall Islands' , 'zovonto' ) ,
				'MQ' => __( 'Martinique' , 'zovonto' ) ,
				'MR' => __( 'Mauritania' , 'zovonto' ) ,
				'MU' => __( 'Mauritius' , 'zovonto' ) ,
				'YT' => __( 'Mayotte' , 'zovonto' ) ,
				'MX' => __( 'Mexico' , 'zovonto' ) ,
				'FM' => __( 'Micronesia' , 'zovonto' ) ,
				'MD' => __( 'Moldova' , 'zovonto' ) ,
				'MC' => __( 'Monaco' , 'zovonto' ) ,
				'MN' => __( 'Mongolia' , 'zovonto' ) ,
				'ME' => __( 'Montenegro' , 'zovonto' ) ,
				'MS' => __( 'Montserrat' , 'zovonto' ) ,
				'MA' => __( 'Morocco' , 'zovonto' ) ,
				'MZ' => __( 'Mozambique' , 'zovonto' ) ,
				'MM' => __( 'Myanmar' , 'zovonto' ) ,
				'NA' => __( 'Namibia' , 'zovonto' ) ,
				'NR' => __( 'Nauru' , 'zovonto' ) ,
				'NP' => __( 'Nepal' , 'zovonto' ) ,
				'NL' => __( 'Netherlands' , 'zovonto' ) ,
				'NC' => __( 'New Caledonia' , 'zovonto' ) ,
				'NZ' => __( 'New Zealand' , 'zovonto' ) ,
				'NI' => __( 'Nicaragua' , 'zovonto' ) ,
				'NE' => __( 'Niger' , 'zovonto' ) ,
				'NG' => __( 'Nigeria' , 'zovonto' ) ,
				'NU' => __( 'Niue' , 'zovonto' ) ,
				'NF' => __( 'Norfolk Island' , 'zovonto' ) ,
				'MP' => __( 'Northern Mariana Islands' , 'zovonto' ) ,
				'KP' => __( 'North Korea' , 'zovonto' ) ,
				'NO' => __( 'Norway' , 'zovonto' ) ,
				'OM' => __( 'Oman' , 'zovonto' ) ,
				'PK' => __( 'Pakistan' , 'zovonto' ) ,
				'PS' => __( 'Palestinian Territory' , 'zovonto' ) ,
				'PA' => __( 'Panama' , 'zovonto' ) ,
				'PG' => __( 'Papua New Guinea' , 'zovonto' ) ,
				'PY' => __( 'Paraguay' , 'zovonto' ) ,
				'PE' => __( 'Peru' , 'zovonto' ) ,
				'PH' => __( 'Philippines' , 'zovonto' ) ,
				'PN' => __( 'Pitcairn' , 'zovonto' ) ,
				'PL' => __( 'Poland' , 'zovonto' ) ,
				'PT' => __( 'Portugal' , 'zovonto' ) ,
				'PR' => __( 'Puerto Rico' , 'zovonto' ) ,
				'QA' => __( 'Qatar' , 'zovonto' ) ,
				'RE' => __( 'Reunion' , 'zovonto' ) ,
				'RO' => __( 'Romania' , 'zovonto' ) ,
				'RU' => __( 'Russia' , 'zovonto' ) ,
				'RW' => __( 'Rwanda' , 'zovonto' ) ,
				'BL' => __( 'Saint Barth&eacute;lemy' , 'zovonto' ) ,
				'SH' => __( 'Saint Helena' , 'zovonto' ) ,
				'KN' => __( 'Saint Kitts and Nevis' , 'zovonto' ) ,
				'LC' => __( 'Saint Lucia' , 'zovonto' ) ,
				'MF' => __( 'Saint Martin (French part)' , 'zovonto' ) ,
				'SX' => __( 'Saint Martin (Dutch part)' , 'zovonto' ) ,
				'PM' => __( 'Saint Pierre and Miquelon' , 'zovonto' ) ,
				'VC' => __( 'Saint Vincent and the Grenadines' , 'zovonto' ) ,
				'SM' => __( 'San Marino' , 'zovonto' ) ,
				'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe' , 'zovonto' ) ,
				'SA' => __( 'Saudi Arabia' , 'zovonto' ) ,
				'SN' => __( 'Senegal' , 'zovonto' ) ,
				'RS' => __( 'Serbia' , 'zovonto' ) ,
				'SC' => __( 'Seychelles' , 'zovonto' ) ,
				'SL' => __( 'Sierra Leone' , 'zovonto' ) ,
				'SG' => __( 'Singapore' , 'zovonto' ) ,
				'SK' => __( 'Slovakia' , 'zovonto' ) ,
				'SI' => __( 'Slovenia' , 'zovonto' ) ,
				'SB' => __( 'Solomon Islands' , 'zovonto' ) ,
				'SO' => __( 'Somalia' , 'zovonto' ) ,
				'ZA' => __( 'South Africa' , 'zovonto' ) ,
				'GS' => __( 'South Georgia/Sandwich Islands' , 'zovonto' ) ,
				'KR' => __( 'South Korea' , 'zovonto' ) ,
				'SS' => __( 'South Sudan' , 'zovonto' ) ,
				'ES' => __( 'Spain' , 'zovonto' ) ,
				'LK' => __( 'Sri Lanka' , 'zovonto' ) ,
				'SD' => __( 'Sudan' , 'zovonto' ) ,
				'SR' => __( 'Suriname' , 'zovonto' ) ,
				'SJ' => __( 'Svalbard and Jan Mayen' , 'zovonto' ) ,
				'SZ' => __( 'Swaziland' , 'zovonto' ) ,
				'SE' => __( 'Sweden' , 'zovonto' ) ,
				'CH' => __( 'Switzerland' , 'zovonto' ) ,
				'SY' => __( 'Syria' , 'zovonto' ) ,
				'TW' => __( 'Taiwan' , 'zovonto' ) ,
				'TJ' => __( 'Tajikistan' , 'zovonto' ) ,
				'TZ' => __( 'Tanzania' , 'zovonto' ) ,
				'TH' => __( 'Thailand' , 'zovonto' ) ,
				'TL' => __( 'Timor-Leste' , 'zovonto' ) ,
				'TG' => __( 'Togo' , 'zovonto' ) ,
				'TK' => __( 'Tokelau' , 'zovonto' ) ,
				'TO' => __( 'Tonga' , 'zovonto' ) ,
				'TT' => __( 'Trinidad and Tobago' , 'zovonto' ) ,
				'TN' => __( 'Tunisia' , 'zovonto' ) ,
				'TR' => __( 'Turkey' , 'zovonto' ) ,
				'TM' => __( 'Turkmenistan' , 'zovonto' ) ,
				'TC' => __( 'Turks and Caicos Islands' , 'zovonto' ) ,
				'TV' => __( 'Tuvalu' , 'zovonto' ) ,
				'UG' => __( 'Uganda' , 'zovonto' ) ,
				'UA' => __( 'Ukraine' , 'zovonto' ) ,
				'AE' => __( 'United Arab Emirates' , 'zovonto' ) ,
				'GB' => __( 'United Kingdom (UK)' , 'zovonto' ) ,
				'US' => __( 'United States (US)' , 'zovonto' ) ,
				'UM' => __( 'United States (US) Minor Outlying Islands' , 'zovonto' ) ,
				'VI' => __( 'United States (US) Virgin Islands' , 'zovonto' ) ,
				'UY' => __( 'Uruguay' , 'zovonto' ) ,
				'UZ' => __( 'Uzbekistan' , 'zovonto' ) ,
				'VU' => __( 'Vanuatu' , 'zovonto' ) ,
				'VA' => __( 'Vatican' , 'zovonto' ) ,
				'VE' => __( 'Venezuela' , 'zovonto' ) ,
				'VN' => __( 'Vietnam' , 'zovonto' ) ,
				'WF' => __( 'Wallis and Futuna' , 'zovonto' ) ,
				'EH' => __( 'Western Sahara' , 'zovonto' ) ,
				'WS' => __( 'Samoa' , 'zovonto' ) ,
				'YE' => __( 'Yemen' , 'zovonto' ) ,
				'ZM' => __( 'Zambia' , 'zovonto' ) ,
				'ZW' => __( 'Zimbabwe' , 'zovonto' ) ,
							)
					)
					) ;
		}

		return $countries ;
	}

}
