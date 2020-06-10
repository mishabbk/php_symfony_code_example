import $ from 'jquery';

const Menu = function() {

    const $accordions = $('.sidebar-menu ul li a');
    const $sidebar = $('.dashboard-sidebar');

    var e = document.getElementById('btn');
    e.addEventListener('click', function() {
        if (this.className == 'on') this.classList.remove('on');
        else this.classList.add('on');
    });

    $('#menuToggle').click(function() {
        $sidebar.toggleClass('open');
    });
    $('.outer').click(function() {
        $sidebar.toggleClass('open');
        $('#btn').removeClass('on');
    });

    $accordions.click(function(){
        $(this).parent().find('.sidebar-submenu').slideToggle();
        $accordions.removeClass('active');
        $(this).addClass('active');
    });

    const context = document.querySelector('.sidebar-menu');

    // collapse all accordion
    const toggleButtons = document.querySelectorAll('[data-tree-up], [data-tree-down]');
    toggleButtons.forEach(el => {
        el.addEventListener('click', () => {
            const isUp = el.dataset.treeUp;
            const $tree = isUp ? $(el.dataset.treeUp) : $(el.dataset.treeDown);
            $tree.find('.has-sub').each((index, item) => {
                if (isUp) {
                    $(item).find('.sidebar-submenu').hide();
                } else {
                    $(item).find('.sidebar-submenu').show();
                }
            });
        });
    });

    // search form
    const searchForm = document.querySelector('.sidebar-form');
    if (searchForm) {
        let searchItems = [];
        const lis = context.querySelectorAll('li:not(.has-sub)');
        lis.forEach((li) => {
            let text = '';
            const a = li.querySelector('a');
            if (a) {
                text += a.textContent || a.innerText;
            }
            searchItems.push({
                item: li,
                text: text.trim().replace(/(?:_|\s\s+)/g, ' '),
            })
        });

        const searchInput = searchForm.querySelector('input[type="text"]');
        const searchIcon = searchForm.querySelector('i');

        // prevent submit
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            return false;
        });

        // toggle icon search / reset
        searchForm.addEventListener('reset', () => {
            searchIcon.classList.remove('fa-times');
            context.querySelectorAll('li').forEach((el) => {
                el.classList.remove('d-none');
                if (el.classList.contains('has-sub') && !el.querySelector('a.active')) {
                    el.querySelector('.sidebar-submenu').style.display = 'none';
                }
            });
        });

        let firstSearch = true;
        // filter
        searchInput.addEventListener('keyup', () => {
            if (!searchInput.value) {
                firstSearch = true;
                searchForm.reset();
                return;
            }
            if (!firstSearch) {
                context.querySelectorAll('.has-sub').forEach((acc) => {
                    acc.querySelector('.sidebar-submenu').style.display = 'block';
                });
            }
            firstSearch = false;

            searchIcon.classList.add('fa-times');
            const filter = searchInput.value.toLowerCase();

            searchItems.forEach((li) => {
                if (li.text.toLowerCase().indexOf(filter) > -1) {
                    li.item.classList.remove('d-none');
                } else {
                    li.item.classList.add('d-none');
                }
            });

            // remove empty accordion
            context.querySelectorAll('.has-sub').forEach((acc) => {
                if (acc.querySelectorAll('li:not(.d-none)').length) {
                    acc.classList.remove('d-none');
                } else {
                    acc.classList.add('d-none');
                }
            });
        });

    }
}();
