/**
 * Handle the "Add new Criteria" button
 *
 */
export const addMultisetCriteria = () => {
    const newElementButton = document.getElementById('new-criteria-button');

    newElementButton.onclick = (event) => {
        event.preventDefault();

        //array with all div values
        let rows         = returnArrayElementsValues('removable-criteria');

        //find if there is a missing number in array
        let missingNumber = returnArrayMissingNumber(rows);

        let nCriteria;

        //the first element to insert must be a missingNumber, otherwiste array.lenght+1
        if(missingNumber !== false) {
            nCriteria   = missingNumber;
        } else {
            nCriteria   = rows.length + 1;
        }

        //Row number must be >= 3 and < 9
        if(nCriteria < 3 || nCriteria > 9 ) {
            return;
        }

        //Create the div
        const newCriteria     = document.createElement('div');

        newCriteria.id        = `criteria-row-container-${nCriteria}`;
        newCriteria.className = `criteria-row removable-criteria`;

        newCriteria.setAttribute("value", nCriteria); //newCriteria.value doesnt' work here

        newCriteria.innerHTML = `
                <label for="multi-set-name-element-${nCriteria}">
                </label>
                <input type="text"
                    name="multi-set-name-element-${nCriteria}"
                    id="multi-set-name-element-${nCriteria}"
                    class="input-text-multi-set"
                    placeholder="New Criteria"
                />
                <span 
                    class="dashicons dashicons-remove yasr-multiset-info-delete criteria-delete" 
                    id="remove-criteria-${nCriteria}"
                    data-id-criteria="${newCriteria.id}"
                    >            
                </span>`;

        if(missingNumber !== false) {
            /**
             * @todo works only if one row at time is removed
             */

            const nextId     = nCriteria + 1; //read todo
            const idNextNode = `criteria-row-container-${nextId}`;
            // Get a reference to the parent node
            const parentDiv = document.getElementById("new-set-criteria-container");

            // Begin test case [ 1 ] : Existing childElement (all works correctly)
            let nextDiv = document.getElementById(idNextNode);
            parentDiv.insertBefore(newCriteria, nextDiv);
        } else {
            document.getElementById('new-set-criteria-container').appendChild(newCriteria);
        }

        //add new event onClick on new button delete
        removeMultisetCriteria();
    }
}

/**
 * Manage the click on buttonDelete
 *
 * @param startFor | 3 At page load, first button delete start on row 3
 */
export const removeMultisetCriteria = (startFor = 3) => {
    //Number of existing rows
    const nOfCriteria = returnArrayElementsValues('removable-criteria').length;

    //add an onclick event for every delete button
    for (let i = startFor; i <= nOfCriteria; i++) {
        const buttonDelete = document.getElementById(`remove-criteria-${i}`);

        buttonDelete.onclick = (event) => {
            let idDivToRemove = buttonDelete.dataset.idCriteria;
            document.getElementById(idDivToRemove).remove();
        }

    }//End for

    //call this again or "Add new criteria will not work after "
    addMultisetCriteria();
}

/**
 * Return an array of int with all the "value" attribute of an element.
 *
 * This function can't be used if the element doesn't have the "value" attribute!!
 *
 * @param className
 * @returns {number[]}
 */
const returnArrayElementsValues = (className) => {
    return [...document.getElementsByClassName(className)]
        .map(el => parseInt(el.attributes.value.value));
}

/**
 * Return the first number missing in an array, and if none is found, just array.length +1
 *
 * @param array
 * @returns {boolean}
 */
const returnArrayMissingNumber = (array) => {
    let missingNumber = false

    //be sure array is sorted
    array.sort()

    //find the first missing number in array
    for (let i = 1; i <= array.length; i++) {
        if (array.indexOf(i) === -1) {
            missingNumber = i;
        }
    }

    return missingNumber;
}