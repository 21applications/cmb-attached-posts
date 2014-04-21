CMB Attached Posts Field
==================

Custom field for Custom Metaboxes and Fields for WordPress (https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress).

Extends coreymcollins version with support for custom post types:

Pass array of post types as 'post_type' element of field array, e.g.:

'fields' => array( 'id' => '_my_field_id', 'name' => 'My Field', 'type' => 'custom_attached_posts', 'post_type' => array( 'post', 'my_post_type' ) )

