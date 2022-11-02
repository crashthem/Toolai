(
    function mainNavHandler() {
        const togglers = document.querySelectorAll('[data-toggle="drop-menu"]')
        const bodyMask = document.querySelector('.body-mask')
        togglers.forEach( toggler => {
            toggler.addEventListener('mouseover', () => {
                bodyMask.classList.add('active')
            })
            toggler.addEventListener('mouseleave', () => {
                bodyMask.classList.remove('active')
            })
        })
        const navlinks = document.querySelectorAll('.main-nav.desctop a')
        navlinks.forEach( link => {
          link.addEventListener('click', event => {
            if(link.classList.contains('active')) {
              event.preventDefault()
            }
          })
        })
    }
)()

function mobileNav() {
  const navButton = document.querySelector('.mobile-nav-button')
  const menu = document.querySelector('.mobile-nav .menu')
  const subMenus = menu.querySelectorAll('.sub-menu')
  navButton.addEventListener('click', () => {
    document.body.classList.toggle('locked')
    navButton.classList.toggle('active')
    menu.classList.toggle('active')
    subMenus.forEach( subMenu => {
      subMenu.classList.remove('active')
    })
  })

  const subMenuButtons = menu.querySelectorAll('[data-toggle="sub-menu"]')
  const subMenuCloseButtons = menu.querySelectorAll('.sub-menu-close')
  subMenuButtons.forEach( button => {
    button.addEventListener('click', event => {
      event.preventDefault()
      const subMenu = event.target.closest('.nav-item').querySelector('.sub-menu')
      subMenu.classList.add('active')
    })
  })
  subMenuCloseButtons.forEach( button => {
    button.addEventListener('click', event => {
      event.stopPropagation()
      const subMenu = event.target.closest('.sub-menu')
      subMenu.classList.remove('active')
    })
  })

}

mobileNav()

function accordionSlider( options = {
  speed: 0
}) {
  const promoAccordion = document.querySelector('.promo.accordion')
  if( promoAccordion ) {
    const height = getComputedStyle(document.querySelector('.promo .slide.active')).height
    promoAccordion.style.height = height
    promoAccordion.style.overflow = 'hidden'
    const slides = document.querySelectorAll('.promo .slide')
    const activeWidth = parseInt(getComputedStyle(document.querySelector('.promo .slide.active')).width)
    slides.forEach((slide) => {
      slide.style.height = height
      slide.style.transition = 'unset'
      slide.addEventListener('mouseover', (event) => {
        slides.forEach((slide) => {
          slide.style.transition = `all ${options.speed * 3 }ms ease`
          slide.style.width = (window.innerWidth - activeWidth) / (slides.length - 1) + 'px'
          slide.classList.remove('active')
      })
        event.target.closest('.slide').classList.add('active')
        event.target.closest('.slide').style.width = activeWidth + 'px'
        event.target.closest('.slide').style.transition = `all ${options.speed}ms ease`
      })
    })
  }

}

if(window.innerWidth >= 840 ) {
  accordionSlider({speed: 300})
}
if (window.innerWidth < 840 && document.querySelector('.promo.accordion')) {
  const promo = document.querySelector('.promo.accordion')
  promo.querySelectorAll('.slide').forEach((slide) => {
    slide.style.cssText = `
      width: initial;
  `
  })
  const promoSlider = tns({
    container: '.promo.accordion',
    items: 1,
    slideBy: 1,
    mouseDrag: true,
    swipeAngle: false,
    speed: 2500,
    arrowKeys: false,
    gutter: 10,
    controls: false,
    preventScrollOnTouch: 'auto',
    nav: false,
    autoplay: true,
    autoplayButtonOutput: false,
    responsive: {
      350: {
        items: 1,
        controls: true,
        edgePadding: 30,
        controls: false,
        preventScrollOnTouch: 'auto',
        nav: false,
      },
      500: {
        items: 1,
        controls: false,
        gutter: 0,
        preventScrollOnTouch: 'auto',
        nav: false,
      },
      1000: {
        items: 1,
        controls: false,
        gutter: 0,
        preventScrollOnTouch: 'auto',
        nav: false,
      },
      1400: {
        items: 1,
        controls: false,
        gutter: 0,
        preventScrollOnTouch: 'auto',
        nav: false,
      },
    },
  })
}


const promoSlider = document.querySelectorAll('.promo .slider')

if(promoSlider.length) {
  const promoSlider = tns({
    container: '.promo .slider',
    items: 1,
    slideBy: 1,
    mouseDrag: true,
    swipeAngle: false,
    speed: 2500,
    arrowKeys: false,
    gutter: 10,
    controls: false,
    preventScrollOnTouch: 'auto',
    nav: false,
    autoplay: true,
    autoplayButtonOutput: false,
    responsive: {
      350: {
        items: 1,
        controls: true,
        edgePadding: 30,
        controls: false,
        preventScrollOnTouch: 'auto',
        nav: false,
      },
      500: {
        items: 1,
        controls: false,
        gutter: 0,
        preventScrollOnTouch: 'auto',
        nav: false,
      },
      1000: {
        items: 2,
        controls: false,
        gutter: 0,
        preventScrollOnTouch: 'auto',
        nav: false,
      },
      1400: {
        items: 2,
        controls: false,
        gutter: 0,
        preventScrollOnTouch: 'auto',
        nav: false,
        gutter: 10,
      },
    },
  })
}

const cardsliders = document.querySelectorAll('.cards-slider')

if(cardsliders.length) {
    cardsliders.forEach( cardslider => {
        const slider = tns({
          container: cardslider,
          items: 1,
          slideBy: 'page',
          mouseDrag: true,
          swipeAngle: false,
          speed: 400,
          arrowKeys: false,
          gutter: 20,
          fixedWidth: 215,
          controls: false,
          preventScrollOnTouch: 'auto',
          nav: false,
          responsive: {
            350: {
              items: 1,
              controls: true,
              edgePadding: 30,
              controls: false,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
            500: {
              items: 2,
              controls: false,
              gutter: 10,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
            1000: {
              items: 4,
              controls: false,
              gutter: 30,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
            1400: {
              items: 5,
              controls: false,
              gutter: 40,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
          },
        })
    })

}
const singleImages = document.querySelector('.single-product__images')
if( singleImages && document.documentElement.clientWidth <= 840 ) {
  const slider = tns({
    container: singleImages,
    items: 1,
    slideBy: 'page',
    mouseDrag: true,
    swipeAngle: false,
    speed: 400,
    arrowKeys: false,
    gutter: 10,
    controls: false,
    preventScrollOnTouch: 'auto',
    nav: false,
  })
}
const cardsliders4 = document.querySelectorAll('.cards-slider-4')

if(cardsliders4.length) {
    cardsliders4.forEach( cardslider => {
        const slider = tns({
          container: cardslider,
          items: 1,
          slideBy: 'page',
          mouseDrag: true,
          swipeAngle: false,
          speed: 400,
          arrowKeys: false,
          gutter: 10,
          controls: false,
          preventScrollOnTouch: 'auto',
          nav: false,
          responsive: {
            350: {
              items: 1,
              controls: true,
              edgePadding: 30,
              controls: false,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
            500: {
              items: 2,
              controls: false,
              gutter: 10,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
            1000: {
              items: 4,
              controls: false,
              gutter: 30,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
            1400: {
              items: 4,
              controls: false,
              gutter: 50,
              preventScrollOnTouch: 'auto',
              nav: false,
            },
          },
        })
    })

}
const cardsliders3 = document.querySelectorAll('.cards-slider-3')

if (cardsliders3.length) {
  cardsliders3.forEach((cardslider) => {
    const slider = tns({
      container: cardslider,
      items: 1,
      slideBy: 'page',
      mouseDrag: true,
      swipeAngle: false,
      speed: 400,
      arrowKeys: false,
      gutter: 10,
      controls: false,
      preventScrollOnTouch: 'auto',
      nav: false,
      responsive: {
        350: {
          items: 1,
          controls: true,
          edgePadding: 30,
          controls: false,
          preventScrollOnTouch: 'auto',
          nav: false,
        },
        500: {
          items: 2,
          controls: false,
          gutter: 10,
          preventScrollOnTouch: 'auto',
          nav: false,
        },
        1000: {
          items: 3,
          controls: false,
          gutter: 30,
          preventScrollOnTouch: 'auto',
          nav: false,
        },
        1400: {
          items: 3,
          controls: false,
          gutter: 50,
          preventScrollOnTouch: 'auto',
          nav: false,
        },
      },
    })
  })
}
const singleProductSlider = document.querySelector('.single-product__slider')
if(singleProductSlider) {
  const slider = tns({
    container: '.single-product__slider .slides',
    items: 1,
    slideBy: 'page',
    mouseDrag: true,
    swipeAngle: false,
    speed: 2500,
    arrowKeys: false,
    /* controls: false, */
    autoplayButtonOutput: false,
    navAsThumbnails: true,
    navPosition: 'bottom',
    navContainer: '.single-product__slider .navigation',
    gutter: 0,
    center: true,
    /* controlsContainer: '.single-product__slider .controls', */
    prevButton: '.prev',
    nextButton: '.next',
  })
}
const sizeGuideSlider = document.querySelector('.size-guide__slider .slides')
if (sizeGuideSlider) {
  const slider = tns({
    container: '.size-guide__slider .slides',
    items: 1,
    slideBy: 1,
    mouseDrag: true,
    swipeAngle: false,
    speed: 2500,
    arrowKeys: false,
    controls: false,
    autoplayButtonOutput: false,
    navAsThumbnails: true,
    navPosition: 'bottom',
    navContainer: '.size-guide__slider .navigation',
    gutter: 50,
  })
}
const wearList = document.querySelector('.wear-list')
if(wearList) {
    const slider = tns({
      container: '.wear-list',
      items: 3,
      slideBy: 1,
      mouseDrag: true,
      swipeAngle: false,
      speed: 2500,
      arrowKeys: false,
      controls: false,
      preventScrollOnTouch: 'auto',
      autoplayButtonOutput: false,
      axis: 'vertical',
      nav: false,
      gutter: 10,
    })
}
const alsoList = document.querySelector('.also-list')
if(alsoList) {
  const slider = tns({
    container: '.also-list',
    items: 1,
    slideBy: 1,
    mouseDrag: true,
    swipeAngle: false,
    speed: 2500,
    arrowKeys: false,
    controls: false,
    autoplayButtonOutput: false,
    nav: false,
    gutter: 10,
    responsive: {
      350: {
        items: 2,
        controls: true,
        edgePadding: 30,
        controls: false,
      },
      500: {
        items: 2,
        controls: false,
        gutter: 0,
      },
      1000: {
        items: 3,
        controls: false,
        gutter: 0,
      },
      1400: {
        items: 4,
        controls: false,
        gutter: 50,
      },
    },
  })
}

function forms() {
  const forms = document.querySelectorAll('form')
  forms.forEach( form => {
    form.addEventListener('submit', event => {
      event.preventDefault()
      /* const inputs = form.querySelectorAll('input')
      inputs.forEach( input => {
        if(input.type === 'email') {
          console.log('email: ',input)
          return
        }
        if(input.type === 'password') {
          console.log('password: ', input)
        }
      }) */
    })
  })
}
forms()

const accordions = document.querySelectorAll('.s-accordion')

if(accordions) {
  accordions.forEach(accordion => {
    accordion
      .querySelector('.accordion-title')
      .addEventListener('click', function () {
        this.closest('.s-accordion').querySelector('.accordion-body').classList.toggle('active')
        this.closest('.s-accordion').querySelector('.accordion-title').classList.toggle('active')
      })
  })
}


const priceFilter = document.querySelector('.price-filter')
function priceFilterHandler() {
  const maxValue = 50000
  const minValue = 0
  const minGap = 1000

  const numberFrom = priceFilter.querySelector('#number-from')
  const numberTo = priceFilter.querySelector('#number-to')

  const rangeFrom = priceFilter.querySelector('#range-from')
  const rangeTo = priceFilter.querySelector('#range-to')
  const rangeGap = priceFilter.querySelector('.range-gap')
  rangeGap.style.left = minValue + '%'
  rangeGap.style.right = maxValue/maxValue*0.1 + '%'

  numberFrom.addEventListener('input', function() {
    if (!(numberTo.value - this.value < minGap)) {
      
    }
  })
  numberTo.addEventListener('change', function() {
    if (!(this.value - numberFrom.value < minGap)) {
      setRangeTo(this.value)
      return
    }
    this.value = Number(numberFrom.value) + minGap
  })


  function setNumberFrom(newValue) {
    numberFrom.value = newValue
  }

  function setNumberTo(newValue) {
    numberTo.value = newValue
  }

  function setRangeFrom(newValue) {
    rangeFrom.value = newValue
  }
  function setRangeTo(newValue) {
    if (!(numberTo.value - this.value < minGap)) {
      rangeGap.style.right = `${100 - (newValue / maxValue) * 100}%`
      rangeTo.value = newValue
      return
    }
    rangeTo.value = rangeFrom.value + minGap
  }

  rangeFrom.addEventListener('input', function() {
    if (!(rangeTo.value - this.value < minGap)) {
      setNumberFrom(this.value)
      rangeGap.style.left = `${(this.value / maxValue) * 100}%`
      return
    }
    this.value = rangeTo.value - minGap
  })

  rangeTo.addEventListener('input', function() {
    if(!(this.value - rangeFrom.value < minGap) ) {
      setNumberTo(this.value)
      rangeGap.style.right = `${100 - (this.value / maxValue) * 100}%`
      return
    }
    this.value = +rangeFrom.value + minGap
  })

  function setRangeFrom(newValue) {
    if (!(numberTo.value - this.value < minGap)) {
      rangeGap.style.left = `${(newValue / maxValue) * 100}%`
      rangeFrom.value = newValue
      return
    }
    rangeFrom.value = rangeTo.value - minGap
    rangeGap.style.right = `${( (Number(numberTo.value) - minGap) / maxValue) * 100}%`
  }
  function setRangeTo(newValue) {
    if (!(numberTo.value - this.value < minGap)) {
      rangeGap.style.right = `${100 - (newValue / maxValue) * 100}%`
      rangeTo.value = newValue
      return
    }
    rangeTo.value = rangeFrom.value + minGap
    rangeGap.style.right = `${100 - ( (Number(numberFrom.value) - minGap) / maxValue) * 100}%`
  }

  numberFrom.addEventListener('change', function () {
    if (!(numberTo.value - this.value < minGap)) {
      setRangeFrom(this.value)
      return
    }
    this.value = Number(numberTo.value) - minGap
  })
  numberTo.addEventListener('change', function () {
    if (!(this.value - numberFrom.value < minGap)) {
      setRangeTo(this.value)
      return
    }
    this.value = Number(numberFrom.value) + minGap
  })
}

if (priceFilter) {priceFilterHandler()}

function activateModals() {
  const openButtons = document.querySelectorAll('[data-toggle="modal"]')
  const closeButtons = document.querySelectorAll('.modal-close')
  const modals = document.querySelectorAll('.modal')

  openButtons.forEach( button => {
    button.addEventListener('click', () => {
      document.querySelector(`[data-name='${button.dataset.modal}']`).classList.add('active')
      document.body.classList.add('locked')
    })
  })
  closeButtons.forEach( button => {
    button.addEventListener('click', () => {
      button.closest('.modal').classList.remove('active')
      document.body.classList.remove('locked')
    })
  })
  modals.forEach(modal => {
    modal.addEventListener('click', event => {
      if(event.target === modal || event.target.classList.contains('close')) {
        modal.classList.remove('active')
        document.body.classList.remove('locked')
      }
    })
  })
}
activateModals()

function tab() {
  const tab = document.querySelector('.tab')
  if (!tab) {
    return
  }
  const tabButtons = tab.querySelectorAll('[data-toggle=tab]')
  const tabItems = tab.querySelectorAll('.tab-item')
  tabButtons.forEach((button) => {
    button.addEventListener('click', () => {
      tabItems.forEach((item) => {
        item.classList.remove('active')
        if (item.dataset.name === button.dataset.target) {
          item.classList.add('active')
        }
      })
      tabButtons.forEach((button) => button.classList.remove('active'))
      button.classList.add('active')
    })
  })
}
tab()

function initSelects() {
  const myselects = document.querySelectorAll('.my-select')

  if (myselects) {
    myselects.forEach((select) => {
      const placeholder = select.querySelector('.my-select .placeholder-text')
      const options = select.querySelector('.options')
      const optionsList = select.querySelectorAll('.option')
      select.addEventListener('click', () => select.classList.toggle('active'))
      optionsList.forEach((option) => {
        option.addEventListener('click', () => {
          select.dataset.value = option.dataset.value
          placeholder.innerHTML = option.innerHTML
          optionsList.forEach((option) => option.classList.remove('active'))
          option.classList.add('active')
        })
      })
    })
  }
}

initSelects()

function productCards() {
  const cards = document.querySelectorAll('.card')
  if(!cards) return
  cards.forEach( card => {
    const sizes = card.querySelectorAll('.card-sizes__item')
    if(!sizes) return
    if(sizes) {
      sizes.forEach(size => {
        size.addEventListener('click', () => {
          size.classList.toggle('active')
        })
      })
    }

    const addToFavoriteButton = card.querySelector('.add-to-favorite')
    if(!addToFavoriteButton) return
    addToFavoriteButton.addEventListener('click', function() {
      this.classList.toggle('active')
    })
  })

}
productCards()

const fileInputs = document.querySelectorAll('.upload-image')
if(fileInputs) {
  fileInputs.forEach(input => {
    input.addEventListener('change', () => {
      const filename = input.value.split('\\')[input.value.split('\\').length - 1]
      const $parent = input.closest('.input-file')
      $parent.querySelector('.file-name').innerHTML = filename
      /* const image = document.createElement('img')
      image.src = input.value
      $parent.insertBefore(image, input) */
    })
  })
}


/* const nav = document.querySelector('.main-nav')
window.addEventListener('scroll', () => {
  if (window.pageYOffset >= parseInt(getComputedStyle(nav).height)) {
    nav.classList.add('fixed')
    return
  }
  nav.classList.remove('fixed')
}) */

/* const partnerContract = document.querySelector('.partnership-contract')
const partnerSelect = document.querySelector('.partnership-select')
partnerSelect.querySelectorAll('.option').forEach( option => {
  option.addEventListener('click', () => {
    partnerContract.href = '/documents/' + option.dataset.value + '.html'
  })
}) */

const initSlider = (el) => {
  if (el) {
    const newslider = tns({
      container: el,
      items: 1,
      slideBy: 'page',
      mouseDrag: true,
      swipeAngle: false,
      speed: 400,
      arrowKeys: false,
      gutter: 0,
      controls: false,
      preventScrollOnTouch: 'auto',
      nav: false,
      center: false,
    })
    document.querySelectorAll('.prev').forEach( button => {
      button.addEventListener('click', event => {
        newslider.goTo('prev')
      })
    })
    document.querySelectorAll('.next').forEach( button => {
      button.addEventListener('click', event => {
        newslider.goTo('next')
      })
    })
  }
}

if (cardsliders4.length) {

  cardsliders4.forEach((slider) => {
    const modal = document.createElement('div')
    modal.classList.add('modal')
    modal.classList.add('slider-modal')
    modal.innerHTML = `
      <div class="modal-content">
          <div class="modal-header">
              <button class="modal-close">
                  <i class="fas fa-times"></i>
              </button>
          </div>
          <div class="modal-body">
              
          </div>
          <div class="controls">
              <div class="prev"><i class="fas fa-angle-left"></i></div>
              <div class="next"><i class="fas fa-angle-right"></i></div>
          </div>
      </div>
    `
    const getSlides = (selector) => {
      const images = selector.querySelectorAll('img')
      return images
    }
    getSlides(slider).forEach(slide => modal.querySelector('.modal-body').append(slide.cloneNode()))
    document.body.append(modal)

    initSlider(modal.querySelector('.modal-body'))  
    const slides = slider.querySelectorAll('img')
    slides.forEach((slide) => {
      slide.addEventListener('click', () => {
        modal.classList.add('active')
        modal.querySelector('.modal-close').addEventListener('click', () => {
          modal.classList.remove('active')
        })
        modal.addEventListener('click', event => {
          if (event.target == modal ) {
            modal.classList.remove('active')
          }
        })
      })
    })
  })
}
