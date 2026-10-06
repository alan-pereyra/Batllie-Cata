/**
 * Batllié - El Ritual de Cata & Experiencia Matera
 * Script de interactividad (batllie-cata.js)
 * 
 * Gestiona:
 *  - Navegación entre diapositivas y capítulos
 *  - Reproducción y conmutación de audio ambiental
 *  - Inclusión/Exclusión dinámica del ritual del mate
 *  - Atajos de teclado (flechas, barra espaciadora)
 *  - Bloqueo de scroll y barra de progreso
 */

(function() {
    'use strict';

    function initBatllieCata(root) {
        if (!root || root._batllieCataInitialized) return;
        root._batllieCataInitialized = true;

        // Bloquear scroll de la página global para inmersión
        document.documentElement.classList.add('has-batllie-cata');
        document.body.classList.add('has-batllie-cata');

        var isMatera = (root.getAttribute('data-mode') === 'matera');
        var allSlides = Array.prototype.slice.call(root.querySelectorAll('.batllie-cata-slide'));
        var currentIndex = 0;

        var progressBar = root.querySelector('.batllie-cata-progress-fill');
        var navFooter = root.querySelector('.batllie-cata-nav-footer');
        var btnPrev = root.querySelector('.batllie-cata-nav-btn-prev');
        var btnNext = root.querySelector('.batllie-cata-nav-btn-next');
        var dotsContainer = root.querySelector('.batllie-cata-dots');

        var btnStart = root.querySelector('.batllie-cata-btn-start');
        var btnRestart = root.querySelector('.batllie-cata-btn-restart');
        var soundToggle = root.querySelector('.batllie-cata-sound-toggle');
        var mateToggle = root.querySelector('.batllie-cata-mate-toggle');
        var audioBtn = root.querySelector('.batllie-cata-audio-btn');
        var audioText = root.querySelector('.batllie-cata-audio-text');
        var audio = root.querySelector('.batllie-cata-audio-element');

        var isAudioPlaying = false;

        function getActiveSlides() {
            var wantsMate = mateToggle ? mateToggle.checked : true;
            return allSlides.filter(function(slide) {
                if (slide.getAttribute('data-slide-id') === 'mate' && !wantsMate) {
                    return false;
                }
                return true;
            });
        }

        function renderDots(activeSlides) {
            if (!dotsContainer) return;
            dotsContainer.innerHTML = '';
            activeSlides.forEach(function(s, idx) {
                var dot = document.createElement('span');
                dot.className = 'batllie-cata-dot' + (idx === currentIndex ? ' active' : '');
                dot.setAttribute('data-dot', idx);
                dot.addEventListener('click', function() {
                    goToSlide(idx);
                });
                dotsContainer.appendChild(dot);
            });
        }

        function updateUI() {
            var activeSlides = getActiveSlides();
            var totalSlides = activeSlides.length;

            if (currentIndex >= totalSlides) {
                currentIndex = totalSlides - 1;
            }
            if (currentIndex < 0) {
                currentIndex = 0;
            }

            allSlides.forEach(function(s) {
                s.classList.remove('active');
            });

            var currentSlide = activeSlides[currentIndex];
            if (currentSlide) {
                currentSlide.classList.add('active');
                currentSlide.scrollTop = 0;
            }

            renderDots(activeSlides);

            var percent = totalSlides > 1 ? (currentIndex / (totalSlides - 1)) * 100 : 0;
            if (progressBar) {
                progressBar.style.width = percent + '%';
            }

            if (currentIndex === 0) {
                if (navFooter) navFooter.style.display = 'none';
            } else {
                if (navFooter) navFooter.style.display = 'flex';
            }

            if (btnPrev) {
                btnPrev.disabled = (currentIndex <= 1);
            }
            if (btnNext) {
                btnNext.disabled = (currentIndex >= totalSlides - 1);
                btnNext.style.display = 'inline-flex';
                if (currentIndex === totalSlides - 2) {
                    btnNext.textContent = isMatera ? 'Finalizar Experiencia →' : 'Finalizar Cata →';
                } else {
                    btnNext.textContent = 'Siguiente →';
                }
            }
        }

        function goToSlide(idx) {
            var activeSlides = getActiveSlides();
            var totalSlides = activeSlides.length;
            if (idx < 0) idx = 0;
            if (idx >= totalSlides) idx = totalSlides - 1;
            currentIndex = idx;
            updateUI();
        }

        function goToSlideById(slideId) {
            var activeSlides = getActiveSlides();
            for (var i = 0; i < activeSlides.length; i++) {
                if (activeSlides[i].getAttribute('data-slide-id') === slideId) {
                    goToSlide(i);
                    return;
                }
            }
        }

        function playAudio() {
            if (!audio) return;
            audio.play().then(function() {
                isAudioPlaying = true;
                root.classList.add('is-playing');
                if (audioText) audioText.textContent = 'Silenciar';
            }).catch(function(err) {
                console.warn('Audio play was prevented:', err);
            });
        }

        function pauseAudio() {
            if (!audio) return;
            audio.pause();
            isAudioPlaying = false;
            root.classList.remove('is-playing');
            if (audioText) audioText.textContent = 'Música';
        }

        function toggleAudio() {
            if (isAudioPlaying) {
                pauseAudio();
            } else {
                playAudio();
            }
        }

        if (btnStart) {
            btnStart.addEventListener('click', function() {
                if (soundToggle && soundToggle.checked) {
                    playAudio();
                } else {
                    pauseAudio();
                }
                goToSlide(1);
            });
        }

        if (btnRestart) {
            btnRestart.addEventListener('click', function() {
                goToSlide(0);
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function() {
                if (currentIndex > 1) {
                    goToSlide(currentIndex - 1);
                }
            });
        }

        if (btnNext) {
            btnNext.addEventListener('click', function() {
                var activeSlides = getActiveSlides();
                if (currentIndex < activeSlides.length - 1) {
                    goToSlide(currentIndex + 1);
                }
            });
        }

        if (audioBtn) {
            audioBtn.addEventListener('click', function() {
                toggleAudio();
            });
        }

        if (mateToggle) {
            mateToggle.addEventListener('change', function() {
                updateUI();
            });
        }

        var boxCards = root.querySelectorAll('.batllie-cata-box-card[data-goto-chapter]');
        boxCards.forEach(function(card) {
            card.addEventListener('click', function() {
                var targetChapter = card.getAttribute('data-goto-chapter');
                if (targetChapter) {
                    goToSlideById(targetChapter);
                }
            });
        });

        // Generic selector for direct navigation to any slide
        var gotoElements = root.querySelectorAll('[data-goto-slide]');
        gotoElements.forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var targetSlideId = el.getAttribute('data-goto-slide');
                if (targetSlideId) {
                    goToSlideById(targetSlideId);
                }
            });
        });

        // Smooth scroll to element within the slide
        var scrollElements = root.querySelectorAll('[data-scroll-to]');
        scrollElements.forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var targetId = el.getAttribute('data-scroll-to');
                if (targetId) {
                    var targetEl = root.querySelector('#' + targetId);
                    if (targetEl) {
                        targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        window.addEventListener('keydown', function(e) {
            var activeSlides = getActiveSlides();
            if (e.key === 'ArrowRight' || e.key === ' ') {
                if (currentIndex < activeSlides.length - 1) {
                    e.preventDefault();
                    goToSlide(currentIndex + 1);
                }
            } else if (e.key === 'ArrowLeft') {
                if (currentIndex > 1) {
                    e.preventDefault();
                    goToSlide(currentIndex - 1);
                }
            }
        });

        updateUI();
    }

    // Inicialización automática
    function autoInit() {
        var roots = document.querySelectorAll('.batllie-cata-root');
        roots.forEach(function(r) {
            initBatllieCata(r);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', autoInit);
    } else {
        autoInit();
    }

    window.initBatllieCata = initBatllieCata;
})();
