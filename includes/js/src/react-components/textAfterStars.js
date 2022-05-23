import {SetInnerHtml} from "./setInnerHtml";

const TextAfterStars = ({post, text}) => {
    //If number_of_votes exists
    if(typeof post.number_of_votes !== "undefined") {
        let text   =  JSON.parse(yasrWindowVar.textAfterVr);
        text = text.replace('%total_count%', post.number_of_votes);
        text = text.replace('%average%', post.rating);
        return (
            <div className='yasr-most-rated-text'>
                <SetInnerHtml html={text} />
            </div>
        )
    }

    return (
        <div className='yasr-highest-rated-text'>
            {text} {post.rating}
        </div>
    );

};

export {TextAfterStars};
