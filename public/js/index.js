import "./styles.css";

import printJS from "print-js";

function printHTML() {
    printJS({
        printable: 'app',
        type: 'html',
        style: '.special-element { visibility: visible; }'
    });
}

document.getElementById("print-button").addEventListener("click", printHTML);
