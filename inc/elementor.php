<?php

function gff_store_locator_map_addons( $widgets_manager ) {

    class GFF_Store_Locator_Map extends \Elementor\Widget_Base {

        public function get_name() {
            return 'gff-store-locator-map';
        }

        public function get_title() {
            return __( 'GFF Store Locator Map', 'ppm-quickstart' );
        }

        public function get_icon() {
            return 'fa fa-code';
        }

        public function get_categories() {
            return [ 'gff' ];
        }

        private function gff_post_list( $post_type ) {

            $args = wp_parse_args( array(
                'post_type'   => $post_type,
                'numberposts' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
            ) );

            $query = get_posts( $args );

            $array = [];
            if ( $query ) {
                foreach ( $query as $post ) {
                    $array[ $post->ID ] = $post->post_title;
                }
            }

            return $array;
        }

        protected function _register_controls() {



            $this->start_controls_section(
                'content_section',
                [
                    'label' => __( 'Configuration', 'plugin-name' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'map_id',
                [
                    'label' => __( 'Select Map', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $this->gff_post_list('gff-store-map'),
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {

            $settings = $this->get_settings_for_display();

            echo do_shortcode('[gff_store_locator id="'.$settings['map_id'].'"]');

        }

    }

    $widgets_manager->register( new \GFF_Store_Locator_Map() );

}
add_action( 'elementor/widgets/register', 'gff_store_locator_map_addons' );