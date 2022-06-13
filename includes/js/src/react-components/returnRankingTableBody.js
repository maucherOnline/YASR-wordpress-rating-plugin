import {ReturnTableTbody} from "./returnTableTbody";
import {ReturnTableHead}  from "./returnTableHead";

const RankingTableBody = (props) => {
    const {data, source, rankingParams, tableId} = props;

    if(source === 'overall_rating' || source === 'author_multi') {
        return (
            <ReturnTableTbody
                data={data}
                tableId={tableId}
                tBodyId={'overall_'+tableId}
                rankingParams={rankingParams}
                show={'table-row-group'}
                source={source}
            />
        )
    }

    else {
        const vvMost      = data.most;
        const vvHighest   = data.highest;
        const display = 'table-row-group';
        const hide    = 'none';

        let defaultView = 'most';
        let styleMost    = display;
        let styleHighest = hide;

        let params = new URLSearchParams(rankingParams);

        if(params.get('view') !== null) {
            defaultView = params.get('view');
        }

        if(defaultView === 'highest') {
            styleMost    = hide;
            styleHighest = display;
        }

        return (
            <>
                <ReturnTableHead
                    tableId={tableId}
                    source={source}
                    defaultView={defaultView}
                />
                <ReturnTableTbody
                    data={vvMost}
                    tableId={tableId}
                    tBodyId={'most-rated-posts-'+tableId}
                    rankingParams={rankingParams}
                    show={styleMost}
                    source={source}
                />
                <ReturnTableTbody
                    data={vvHighest}
                    tableId={tableId}
                    tBodyId={'highest-rated-posts-'+tableId}
                    rankingParams={rankingParams}
                    show={styleHighest}
                    source={source}
                />
            </>
        )
    }
}

export {RankingTableBody};
