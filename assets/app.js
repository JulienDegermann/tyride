/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/js/bootstrap.bundle';
import './styles/app.scss';

const body = document.querySelector('body');
const ranges = document.querySelectorAll('.range');

const menuButton = document.querySelector('#menu-button');
const profileMenu = document.querySelector('#profile-menu')

if (menuButton) {
    menuButton.addEventListener('click', () => {
        profileMenu.classList.toggle('active');
        body.classList.toggle('no-scroll');
        

    })
}

const filterToggler = document.querySelector('#filter-toggler');
const filter = document.querySelector('#filter');


const expandMore = document.createElement('div');
expandMore.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
<path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z" />
</svg>`

const expandLess = document.createElement('div');
expandLess.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
<path d="m296-345-56-56 240-240 240 240-56 56-184-184-184 184Z" />
</svg>
`;


if (filterToggler) {
    filterToggler.addEventListener('click', () => {
        filter.style.height = 0;
        filter.classList.toggle('h-100');
        filter.classList.toggle('py-2');
        filter.parentElement.classList.toggle('mb-5');
        filter.classList.contains('h-100') ? filterToggler.textContent = 'Masquer les filtres' : filterToggler.textContent = 'Afficher les filtres';
        filter.classList.contains('h-100') ? filterToggler.append(expandMore) : filterToggler.append(expandLess);
    })
}


// range filters (sliders and cursors)
ranges.forEach(range => {
    // get min and max input of each range
    const inputMin = Array.from(range.children).find(child => child.classList.contains('min'));
    const inputMax = Array.from(range.children).find(child => child.classList.contains('max'));

    // create slider and its cursors
    const slider = document.createElement('div');
    const sliderMin = document.createElement('div');
    const sliderMax = document.createElement('div');
    slider.classList.add('slider');
    sliderMin.classList.add('slider-min');
    sliderMax.classList.add('slider-max');


    const showMin = document.createElement('div');
    const showMax = document.createElement('div');
    sliderMin.append(showMin);
    sliderMax.append(showMax);
    showMin.innerText = inputMin.value
    showMax.innerText = inputMax.value

    // for each range, create a slider with 2 cursors and set the cursors position
    range.append(slider);
    slider.append(sliderMin, sliderMax);
    sliderMin.style.left = inputMin.value / inputMin.max * 100 + '%';
    sliderMax.style.left = inputMax.value / inputMax.max * 100 + '%';

    // position x min & max of the slider + slider width
    const start = slider.getBoundingClientRect().left;
    const end = slider.getBoundingClientRect().right;
    const sliderWidth = slider.getBoundingClientRect().width;


    // function for track mouse then move MIN cursor and set minValue to input
    const moveMin = (e) => {
        // get back newPos
        const touch = e.touches
        const mouseX = e.touches ? e.touches[0].clientX : e.clientX
        const maxPos = sliderMax.getBoundingClientRect().left

        let newPos;
        if (mouseX >= maxPos) {
            console.log('un')
            newPos = maxPos;
        }
        else if (mouseX < start) {
            console.log('deux')
            newPos = start;
        }
        else if (mouseX > end) {
            console.log('trois')
            newPos = end;
        }
        else {
            console.log('quatre')
            newPos = mouseX;
        }

        console.log(
            `curseur : ${mouseX}
            start : ${start}
            end : ${end}
            newPos : ${newPos}
            width : ${sliderWidth}`
        )

        sliderMin.style.left = (newPos - start) * 100 / sliderWidth + '%';
        const newValue = parseInt(inputMin.min) + ((newPos - start) / (end - start)) * (inputMin.max - inputMin.min);
        inputMin.value = Math.floor(newValue);
        showMin.innerText = Math.floor(inputMin.value);
    }

    // function for track mouse then move MAX cursor and set maxValue to input
    const moveMax = (e) => {

        // get back newPos
        const touch = e.touches

        const mouseX = e.touches ? e.touches[0].clientX : e.clientX
        const minPos = sliderMin.getBoundingClientRect().left

        let newPos;
        if (mouseX <= minPos) {
            newPos = minPos;
        }
        else if (mouseX < start) {
            newPos = start;
        }
        else if (mouseX > end) {
            newPos = end;
        }
        else {
            newPos = mouseX;
        }

        sliderMax.style.left = (newPos - start) * 100 / sliderWidth + '%';
        const newValue = parseInt(inputMax.min) + ((newPos - start) / (end - start)) * (inputMax.max - inputMax.min);
        inputMax.value = Math.floor(newValue);
        showMax.innerText = Math.floor(inputMax.value);
    }

    // functions to activate mousetrack on min nore max cursor press (check if works on touch)
    sliderMin.addEventListener('mousedown', () => {
        document.addEventListener('mousemove', moveMin)
    });
    sliderMin.addEventListener('touchstart', () => {
        document.addEventListener('touchmove', moveMin)
    });

    sliderMax.addEventListener('mousedown', (e) => {
        document.addEventListener('mousemove', moveMax);
    });
    sliderMax.addEventListener('touchstart', (e) => {
        document.addEventListener('touchmove', moveMax);
    });

    document.addEventListener('mouseup', () => {
        document.removeEventListener('mousemove', moveMin);
        document.removeEventListener('mousemove', moveMax);
    });

    document.addEventListener('touchend', () => {
        document.removeEventListener('touchmove', moveMin);
        document.removeEventListener('touchmove', moveMax);
    });


})


// alert popup auto remove
const popups = document.querySelectorAll('.alert')

popups.forEach((popup) => {
    setTimeout((e) => {
        const close = Array.from(popup.children).find(child => child.classList.contains('close'))
        close.click()
    }, 3000)
})