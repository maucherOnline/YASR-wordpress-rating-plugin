import {YasrRankingTable} from "../react-components/returnRankingTable";

const  {render, useState, useEffect} = wp.element;

/*
* Returns an array with the REST API urls
*
* @author Dario Curvino <@dudo>
* @since  2.5.7
*
* @return array of urls
*/
const returnRestUrl = (rankingParams, source, nonce) => {

    let queryParams       = ((rankingParams !== '') ? rankingParams : '');
    let dataSource        = source;
    const nonceString     = '&nonce_rankings='+nonce;
    let urlYasrRanking;

    let cleanedQuery = '';

    if (queryParams !== '' && queryParams !== false) {
        let params = new URLSearchParams(queryParams);

        if(params.get('order_by') !== null) {
            cleanedQuery += 'order_by='+params.get('order_by');
        }

        if(params.get('limit') !== null) {
            cleanedQuery += '&limit='+params.get('limit');
        }

        if(params.get('start_date') !== null && params.get('start_date') !== '0') {
            cleanedQuery += '&start_date='+params.get('start_date');
        }

        if(params.get('end_date') !== null && params.get('end_date') !== '0') {
            cleanedQuery += '&end_date='+params.get('end_date');
        }

        if(params.get('ctg') !== null) {
            cleanedQuery += '&ctg='+params.get('ctg');
        }
        else if(params.get('cpt') !== null) {
            cleanedQuery += '&cpt='+params.get('cpt');
        }

        if (cleanedQuery !== '') {
            cleanedQuery = cleanedQuery.replace(/\s+/g, '');
            cleanedQuery  = '&'+cleanedQuery;
        }

        if(dataSource === 'visitor_multi' || dataSource === 'author_multi') {
            if(params.get('setid') !== null) {
                cleanedQuery += '&setid=' + params.get('setid');
            }
        }

    } else {
        cleanedQuery = '';
    }

    if(dataSource === 'author_ranking' || dataSource === 'author_multi') {
        urlYasrRanking = [yasrWindowVar.ajaxurl + '?action=yasr_load_rankings&source=' + dataSource + cleanedQuery + nonceString];
    }
    else {
        let requiredMost    = '';
        let requiredHighest = '';

        if(queryParams !== '') {
            let params = new URLSearchParams(queryParams);
            if (params.get('required_votes[most]') !== null) {
                requiredMost = '&required_votes=' + params.get('required_votes[most]');
            }

            if (params.get('required_votes[highest]') !== null) {
                requiredHighest = '&required_votes=' + params.get('required_votes[highest]');
            }
        }

        urlYasrRanking = [
            yasrWindowVar.ajaxurl + '?action=yasr_load_rankings&show=most&source='    + dataSource + cleanedQuery + requiredMost + nonceString,
            yasrWindowVar.ajaxurl + '?action=yasr_load_rankings&show=highest&source=' + dataSource + cleanedQuery + requiredHighest + nonceString
        ];

    }

    return urlYasrRanking;
}

/***
 * @param props
 * @returns {JSX.Element}
 */
const YasrRanking = ({tableId, source, params, nonce}) => {

    const tBodyParams = {
        tableId: tableId,
        source:  source,
        rankingParams: params
    }

    const [error,         setError]       = useState(null);
    const [isLoaded,      setIsLoaded]    = useState(false);
    const [rankingData,   setRankingData] = useState([]);

    /**
     * Return ranking Data from html, and print console.info if not error
     *
     * @param ajaxDisabled
     * @returns {any}
     */
    const setDataFromHtml = (ajaxDisabled = false) => {
        const rankingData = JSON.parse(document.getElementById(tableId).dataset.rankingData);

        if(ajaxDisabled === true) {
            console.info('Ajax Disabled, getting data from source');
        }

        setRankingData(rankingData);
    }

    /**
     * Do the fetch
     */
    const setDataFromFetch = () => {
        let data = [];

        //get the rest urls
        const urlYasrRankingApi = returnRestUrl(params, source, nonce);

        Promise.all(urlYasrRankingApi.map((url) =>
            fetch(url)
                .then(response => {
                    if (response.ok === true) {
                        return response.json();
                    } else {
                        console.info('Ajax Call Failed. Getting data from source')
                        return 'KO';
                    }
                })
                /**
                 * If response is not ok, get data from global var
                 */
                .then(response => {
                    if (response === 'KO') {
                        setDataFromHtml();
                    } else {
                        if(response.source === 'overall_rating' || response.source === 'author_multi') {
                            if(response.source === 'overall_rating') {
                                data = response.data_overall;
                            } else {
                                data = response.data_mv;
                            }
                        }
                        //if data is from visitor votes, create an array like this
                        //data[most]
                        //data[highest]
                        else {
                            data[response.show] = response.data_vv
                        }
                        //only set ranking data here
                        setRankingData(data);
                    }
                })
                .catch((error) => {
                    setDataFromHtml();
                    console.info(error);
                })
        ))
        //At the end of promise all, set isLoaded to true
        .then(r => {
            setIsLoaded(true)
        })
        .catch((error) => {
            setDataFromHtml()
            console.info((error));
        });

    }

    useEffect( () => {
        //If ajax is disabled, use global value
        if (yasrWindowVar.ajaxEnabled !== 'yes') {
            setDataFromHtml(true);
            setIsLoaded(true);
        } else {
            if (source) {
                setDataFromFetch();
            } else {
                setError('Invalid Data Source');
            }
        }
    }, []);

    return (
        <>
            <YasrRankingTable error={error} isLoaded={isLoaded} data={rankingData} {...tBodyParams} />
        </>
    )
}

export function yasrDrawRankings () {
    //check if there is some shortcode with class yasr-table-chart
    const yasrRankingsInDom = document.getElementsByClassName('yasr-stars-rankings');

    if (yasrRankingsInDom.length > 0) {
        for (let i = 0; i < yasrRankingsInDom.length; i++) {
            const tableId      = yasrRankingsInDom.item(i).id;
            const source       = JSON.parse(yasrRankingsInDom.item(i).dataset.rankingSource);
            const params       = JSON.parse(yasrRankingsInDom.item(i).dataset.rankingParams);
            const nonce        = JSON.parse(yasrRankingsInDom.item(i).dataset.rankingNonce);
            const rankingTable = document.getElementById(tableId);

            render(<YasrRanking source={source} tableId={tableId} params={params} nonce={nonce} />, rankingTable);
        }
    }
}

//Drow Rankings
yasrDrawRankings();