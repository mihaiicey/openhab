<?php
/**
 * Eliminare plata cu cardul si transfer bancar pentru categoria YourPack
 */

function disable_payment_method_for_yourpack( $gateways ) {
    if( is_admin() )
        return $gateways;

    $category_slugs = array( 'yourpack');
    $category_slugs2 = array( 'uncategorized', 'accesorii', 'big-green-egg', 'dry-ager', 'gift', 'mwb_wgm_giftcard', 'premium-angus', 'premiumangus-english');
    $category_ids = get_terms( array( 'taxonomy' => 'product_cat', 'slug' => $category_slugs, 'fields' => 'ids' ) );

    foreach ( WC()->cart->get_cart() as $item ) {
        $product = $item['data'];
        if ( $product && array_intersect( $category_ids, $product->get_category_ids() ) ) {
            unset( $gateways['euplatesc'], $gateways['bacs']);
            break;
        }
    }
    return $gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'disable_payment_method_for_yourpack' );


/**
 * Eliminare "Cerere oferta" pentru toate categoriile
 */

function disable_cerere_oferta_for_all_categories( $gateways ) {
    if( is_admin() )
        return $gateways;
    $category_slugs = array( 'uncategorized', 'accesorii', 'big-green-egg', 'dry-ager', 'gift', 'mwb_wgm_giftcard', 'premium-angus', 'premiumangus-english');

    $category_ids = get_terms( array( 'taxonomy' => 'product_cat', 'slug' => $category_slugs, 'fields' => 'ids' ) );

    foreach ( WC()->cart->get_cart() as $item ) {

        $product = $item['data'];

        if ( $product && array_intersect( $category_ids, $product->get_category_ids() ) ) {

            unset( $gateways['cheque']);
            unset( $gateways['offline_gateway']);
            break;

        }

    }

    return $gateways;

}
