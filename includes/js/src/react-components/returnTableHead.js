/**
 * Print Thead Ranking Table Head
 *
 * @author Dario Curvino <@dudo>
 * @since  2.5.7
 *
 * @return {JSX.Element} - html <thead> element
 */
const ReturnTableHead = (props) => {
    const {tableId, source, defaultView} = props;

    const idLinkMost    = 'link-most-rated-posts-'+tableId;
    const idLinkHighest = 'link-highest-rated-posts-'+tableId;

    if(source !== 'author_ranking') {
        let containerLink = <span>
                                    <span id={idLinkMost}>
                                        {JSON.parse(yasrWindowVar.textMostRated)}
                                    </span>&nbsp;|&nbsp;
            <a href='#' id={idLinkHighest} onClick={this.switchTBody.bind(this)}>
                                        {JSON.parse(yasrWindowVar.textHighestRated)}
                                    </a>
                                 </span>

        if(defaultView === 'highest') {
            containerLink = <span>
                                    <span id={idLinkHighest} >
                                        {JSON.parse(yasrWindowVar.textHighestRated)}
                                    </span>&nbsp;|&nbsp;
                <a href='#' id={idLinkMost} onClick={this.switchTBody.bind(this)}>
                                        {JSON.parse(yasrWindowVar.textMostRated)}
                                    </a>
                                 </span>
        }

        return (
            <thead>
            <tr className='yasr-rankings-td-colored yasr-rankings-heading'>
                <th>{JSON.parse(yasrWindowVar.textLeftColumnHeader)}</th>
                <th>
                    {JSON.parse(yasrWindowVar.textOrderBy)}:&nbsp;&nbsp;
                    {containerLink}
                </th>
            </tr>
            </thead>
        )
    }

    return (<></>)
}

export {ReturnTableHead};