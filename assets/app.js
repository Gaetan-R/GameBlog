/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

document.querySelector("#watchlist").addEventListener('click', addToWatchlist);
function addToWatchlist(event) {
    event.preventDefault();

    let watchlistLink = event.currentTarget;
    let link = watchlistLink.href;

    fetch(link)

        .then(res => res.json())

        .then(function(res) {
            let watchlistIcon = watchlistLink.firstElementChild;
            if (res.isInWatchlist) {
                watchlistIcon.classList.remove('far');
                watchlistIcon.classList.add('fas');
            } else {
                watchlistIcon.classList.remove('fas');
                watchlistIcon.classList.add('far');
            }
        });

}