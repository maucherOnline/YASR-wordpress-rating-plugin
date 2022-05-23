import {decodeEntities} from "@wordpress/html-entities";

/**
 * Left column for a table, with post link and title
 *
 * @author Dario Curvino <@dudo>
 * @since  3.0.8
 *
 * @param {string} colClass - Column class name
 * @param {Object} post     - Object with post link and title
 *
 * @return {JSX.Element} - html <td> element
 */

const ReturnTableColumnLeft = ({colClass, post}) => {
    return (
        <td className={colClass}>
            <a href={post.link}>{decodeEntities(post.title)}</a>
        </td>
    )
};

export {ReturnTableColumnLeft};