<?php
namespace CUMULUS\Wordpress\PostsTableShortcode;
/**
 * Plugin Name: Post Table Shortcode
 * Plugin URI: https://github.com/cumulus-digital/wp-post-table-shortcode
 * GitHub Plugin URI: https://github.com/cumulus-digital/wp-post-table-shortcode
 * Description: Provides a Shortcode for including a simple table of posts
 * Version: 0.4
 * Author: vena
 * License: UNLICENSED
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

const PLUGIN_NAME = 'wp-post-table-shortcode';
const PREFIX = 'wp-post-table-shortcode';
const TXTDOMAIN = PREFIX;
const BASEPATH = PLUGIN_NAME;
const BASE_FILENAME = PLUGIN_NAME . DIRECTORY_SEPARATOR . 'plugin.php';

function post_table_shortcode($attr) {
    $attr = \shortcode_atts([
        'category' => null,
        'tag' => null,
        'order' => 'desc',
        'orderby' => 'date',
        'before' => null,
        'after' => date('Y') - 1,
        'max' => -1,
        'date_format' => 'n/j/y'
    ], $attr, 'post_table');

    $posts = (new \WP_Query([
        'category_name' => $attr['category'],
        'tag' => $attr['tag'],
        'date_query' => array(
            array(
                'after' => $attr['after'],
                'before' => $attr['before']
            )
        ),
        'orderby' => $attr['orderby'],
        'order' => $attr['order'],
        'posts_per_page' => $attr['max'],
    ]));

    ob_start();
?>
    <figure class="wp-block-table is-style-stripes">
        <table>
            <tbody>
                <?php while($posts->have_posts()): $posts->the_post(); ?>
                <tr>
                    <td class="has-text-align-right" data-align="right">
                        <?php echo \get_the_date($attr['date_format']); ?>
                    </td>
                    <td>
                        <?php the_title( sprintf( '<a href="%s">', \esc_url( \get_permalink() ) ), '</a>' ); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </figure>
<?php
    $output = ob_get_clean();
    \wp_reset_postdata();

    return $output;
}
\add_shortcode('posts-table', __NAMESPACE__ . '\\post_table_shortcode');
