$(document).ready(function() {
    //ajax za korpu
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        console.log('Product ID:', productId);
    
        $.ajax({
            url: 'models/add_to_cart.php',
            method: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                console.log('Ajax Response:', response);
                if (response.success) {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error:', error);
                alert('An error occurred while adding the product to the cart');
                console.log(xhr.responseText);
            }
        });
    });

    $('.remove-product-btn').click(function(e) {
        e.preventDefault();
        
        var productId = $(this).data('product-id');
        var rowToRemove = $(this).closest('tr'); 
        
        $.ajax({
            url: 'models/remove_from_cart.php',
            method: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                console.log('Ajax Response:', response);
                if(response.success) {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Ajax Error:', error);
                alert('An error occurred while removing the product from the cart');
            }
        });
    });


    $('#proceed-btn').click(function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'models/place_order.php',
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log('Ajax Response:', response);
            if (response.success) {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Ajax Error:', error);
            console.error('Response Text:', xhr.responseText);
            alert('An error occurred while placing the order');
        }
    });
    });
    



    //za unos proizvoda
    $('#btnInsert').on('click', function(e) {
        e.preventDefault(); 

        
        var formData = {
            pname: $('#pname').val(),
            pdesc: $('#pdesc').val(),
            ppath: $('#ppath').val(),
            pprice: $('#pprice').val(),
            pcat: $('#pcat').val()
        };

        
        $.ajax({
            url: 'models/insert_product.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    
                    alert(response.message);
                    // da se obrise tekst iz inputa nakon unosa
                    $('#pname, #pdesc, #ppath, #pprice, #pcat').val('');
                } else {
                    
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                
                alert('An error occurred while processing your request.');
            }
        });
    });


    // za update
    $('#btnUpdate').on('click', function(e) {
        e.preventDefault(); 
        
        var formData = {
            id_prod: $('#id_prod').val(),
            pname: $('#pname').val(),
            pdesc: $('#pdesc').val(),
            ppath: $('#ppath').val(),
            pprice: $('#pprice').val(),
            pcat: $('#pcat').val()
        };
        
        
        $.ajax({
            url: 'models/update_product.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                
                if (response.success) {
                    alert(response.message); 
                    window.location.reload(); // za reload stranice
                } else {
                    alert(response.message); 
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error: Failed to update product');
            }
        });
    });

    // Za prikaz teksta u inputima i enable update
    $(document).on('click', '.btn-edit', function() {
        
        var id = $(this).data('id');

        $('#id_prod').val(id);
        
        
        $.ajax({
            url: 'models/get_product_data.php', 
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                
                $('#pname').val(response.name_prod);
                $('#pdesc').val(response.description);
                $('#ppath').val(response.path);
                $('#pprice').val(response.price);
                $('#pcat').val(response.name_cat);

                
                $('#btnUpdate').removeAttr('disabled').css({'opacity': '1', 'cursor': 'pointer'});

            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });


    //za brisanje proizvoda
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');

        $.ajax({
            url: 'models/delete_product.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.reload(); 
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error: Failed to delete product');
            }
        });
    });


    // dodavanje features
    $('#btnAddFeature').on('click', function(e) {
        e.preventDefault();

        var formData = {
            product_name: $('#product_name').val(), 
            feature_name: $('#feature_name').val(),
            feature_value: $('#feature_value').val()
        };

        $.ajax({
            url: 'models/add_feature.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.reload()
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while processing your request.');
            }
        });
    });


});


document.addEventListener('DOMContentLoaded', function () {
    
    loadProducts(1);

    
    $('#sort').on('change', function() {
        var sortOption = $(this).val();
        loadProducts(1, sortOption); // ucitavanje proizvoda sa odabranom opcijom
    });

    // sort i paginacija
    function loadProducts(page = 1, sortOption = 'default') {
        fetch(`models/pagination.php?pagination=${page}&sort=${sortOption}`)
            .then(response => response.json())
            .then(data => {
                // clear
                const productsContainer = document.querySelector('#products-container');
                if (productsContainer) {
                    productsContainer.innerHTML = '';
                } else {
                    console.error('Products container not found');
                    return;
                }

                // prikaz
                data.products.forEach(product => {
                    const productHTML = `
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="border border-primary rounded position-relative vesitable-item">
                                <div class="fruite-img">
                                    <a href="shop-detail.php?id=${product.id_prod}">
                                        <img src="assets/${product.path}" class="img-fluid w-100 rounded-top" alt="${product.name_prod}" />
                                    </a>
                                </div>
                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">${product.name_cat}</div>
                                <div class="p-4 border border-top-0 rounded-bottom">
                                    <h4>${product.name_prod}</h4>
                                    <p>${product.description.length > 50 ? product.description.substr(0, 50) + '...' : product.description}</p>
                                    <div class="text-center">
                                        <p class="text-dark fs-5 fw-bold mb-0">$${product.price}</p>
                                    </div>
                                    <div class="row button">
                                        <button class="add-to-cart btn border border-secondary rounded-pill px-3 text-primary" data-product-id="${product.id_prod}">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    productsContainer.insertAdjacentHTML('beforeend', productHTML);
                });

                // Update paginacije
                const paginationContainer = document.querySelector('#pagination-container');
                if (paginationContainer) {
                    let totalPages = Math.ceil(data.totalProducts / data.productsPerPage);
                    paginationContainer.innerHTML = '';

                    if (data.paginationPage > 1) {
                        paginationContainer.innerHTML += `<a class="rounded" href="#" data-page="${data.paginationPage - 1}">&laquo; Prev</a>`;
                    }

                    for (let i = 1; i <= totalPages; i++) {
                        paginationContainer.innerHTML += `<a class="rounded ${i === data.paginationPage ? 'active' : ''}" href="#" data-page="${i}">${i}</a>`;
                    }

                    if (data.paginationPage < totalPages) {
                        paginationContainer.innerHTML += `<a class="rounded" href="#" data-page="${data.paginationPage + 1}">Next &raquo;</a>`;
                    }

                    paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
                        link.addEventListener('click', function(event) {
                            event.preventDefault();
                            const page = parseInt(this.getAttribute('data-page'));
                            loadProducts(page, $('#sort').val());
                        });
                    });
                } else {
                    console.error('Pagination container not found');
                }
            })
            .catch(error => console.error('Error fetching products:', error));
    }
});



    
(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);


    // Fixed Navbar
    $(window).scroll(function () {
        if ($(window).width() < 992) {
            if ($(this).scrollTop() > 55) {
                $('.fixed-top').addClass('shadow');
            } else {
                $('.fixed-top').removeClass('shadow');
            }
        } else {
            if ($(this).scrollTop() > 55) {
                $('.fixed-top').addClass('shadow').css('top', -55);
            } else {
                $('.fixed-top').removeClass('shadow').css('top', 0);
            }
        } 
    });
    
    
   // Back to top button
   $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
        $('.back-to-top').fadeIn('slow');
    } else {
        $('.back-to-top').fadeOut('slow');
    }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Testimonial carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 2000,
        center: false,
        dots: true,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:1
            },
            992:{
                items:2
            },
            1200:{
                items:2
            }
        }
    });


    // vegetable carousel
    $(".vegetable-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        center: false,
        dots: true,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });


    // Modal Video
    $(document).ready(function () {
        var $videoSrc;
        $('.btn-play').click(function () {
            $videoSrc = $(this).data("src");
        });
        console.log($videoSrc);

        $('#videoModal').on('shown.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
        })

        $('#videoModal').on('hide.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc);
        })
    });



    // Product Quantity
    $('.quantity button').on('click', function () {
        var button = $(this);
        var oldValue = button.parent().parent().find('input').val();
        if (button.hasClass('btn-plus')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        button.parent().parent().find('input').val(newVal);
    });

})(jQuery);

