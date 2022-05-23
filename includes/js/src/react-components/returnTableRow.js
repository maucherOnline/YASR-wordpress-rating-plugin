import {ReturnTableColumnLeft} from "./returnTableColumnLeft";
import {ReturnTableColumnRight} from "./returnTableColumnRight";

/**
 * Print row for Ranking Table
 *
 * @author Dario Curvino <@dudo>
 * @since  3.0.8
 *
 * @param props
 * @param {string} props.source   - Source of data
 * @param {Object} props.post     - Object with post attributes
 *
 * @return {JSX.Element} - html <tr> element
 */

const ReturnTableRow = (props) => {
    return (
        <tr className={props.trClass}>
            <ReturnTableColumnLeft   colClass={props.leftClass} post={props.post} />
            <ReturnTableColumnRight  {...props} />
        </tr>
    )
};

export {ReturnTableRow};
