/**
 * Handle the "Add new Criteria" button
 *
 */
export const addMultisetCriteria = () => {

    //This is the container of all criteria input
    const parentDiv = document.getElementById('new-set-criteria-container');

    const newElementButton = document.getElementById('new-criteria-button');

    newElementButton.onclick = (event) => {
        event.preventDefault();

        //array with all div values
        let rows         = returnArrayElementsValues('removable-criteria');

        //find if there is a missing number in array
        let missingNumber = returnArrayMissingNumber(rows);

        let nCriteria;

        //the first element to insert must be a missingNumber, otherwise array.lenght+1
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
        const newDiv     = createNewCriteria(nCriteria);

        if(missingNumber !== false) {
            //value to increase nCriteria
            let j = 1;

            for(let i=3; i<9; i++) {
                let nextId     = nCriteria + j;
                let idNextNode = `criteria-row-container-${nextId}`;

                //if idNextNode exists, insert the new div before
                if(!!document.getElementById(idNextNode) === true) {
                    let nextDiv = document.getElementById(idNextNode);
                    parentDiv.insertBefore(newDiv, nextDiv);

                    //job done, break the loop
                    break;
                }

                //otherwise increase J
                j++;
            }
        }
        //just do appendChild if we're adding and no field was removed
        else {
            document.getElementById('new-set-criteria-container').appendChild(newDiv);
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

        if(buttonDelete !== null) {
            buttonDelete.onclick = (event) => {
                let idDivToRemove = buttonDelete.dataset.idCriteria;
                document.getElementById(idDivToRemove).remove();
            }
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
            break; //break at first missing number
        }
    }

    return missingNumber;
}

/**
 * Return a row with new criteria
 *
 * @returns {*}
 */
const createNewCriteria = (nCriteria) => {
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

    return newCriteria;
}