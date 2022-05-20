import React     from 'react';
import striptags from "striptags";

/**
 * Strip a string to only allow <strong> and <p> tag (no XSS possible), and return it inside a span
 *
 * @param props
 * @returns {JSX.Element}
 */
const YasrSetInnerHtml = (props) => {
    return (
        <div dangerouslySetInnerHTML={{__html: striptags(props.html, '<strong><p>')} }></div>
    );
};

export {YasrSetInnerHtml};
