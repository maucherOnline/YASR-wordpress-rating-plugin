import React from 'react';

/**
 * Call window function yasrSetRaterValue
 *
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const InvokeRater = (props) => {
    let {size, htmlId, element, step, readonly, rating} = props;

    return (
        <div id={htmlId} ref={() =>
            yasrSetRaterValue(size, htmlId, element, step, readonly, rating)
        }>
        </div>
    );
};

export {InvokeRater};
