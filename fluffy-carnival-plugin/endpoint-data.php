<?php 
    header( 'Content-Type: application/json' );
    $post = get_queried_object();
    echo json_encode( get_post_meta($post -> ID, '_fc_message') ); 
?>