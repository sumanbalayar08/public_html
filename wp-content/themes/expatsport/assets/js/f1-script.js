$(document).ready(function () {
  console.log("Tickets Amount:", electraVars.tickets_amount);
  console.log("Package Price:", electraVars.package_price);

  const $dropdowns = $(".dropdown-slider");
  const $ticketOptions = $(".ticket-package");
  let basicpackagePrice = 0;

  function expandStep(stepIndex, packageType) {
    const $dropdown = $dropdowns.filter(function () {
      return (
        $(this).data("step") == stepIndex &&
        $(this).data("package") == packageType
      );
    });

    if ($dropdown.length) {
      const $content = $dropdown.find(".collapsible-container");
      const $tab = $dropdown.find(".dropdown-slider-tab");
      const $icon = $tab.find(".icon");

      $content.addClass("expanding");
      $icon.addClass("active");

      setTimeout(() => {
        $("html, body").animate(
          {
            scrollTop: $dropdown.offset().top,
          },
          200
        );
      }, 200);
    }
  }

  function showStepsForPackage(packageType) {
    $dropdowns.each(function (index) {
      const $dropdown = $(this);
      const dropdownPackage = $dropdown.data("package");

      if (dropdownPackage === packageType || index === 0) {
        $dropdown.removeClass("hidden");

        if (index !== 0) {
          const $content = $dropdown.find(".collapsible-container");
          const $icon = $dropdown.find(".icon");
          $content.removeClass("expanding");
          $icon.removeClass("active");
        }
      } else {
        $dropdown.addClass("hidden");
      }
    });
  }

  $ticketOptions.on("click", function () {
    const selectedPackage = $(this).data("package");
    showStepsForPackage(selectedPackage);

    if (selectedPackage === "basic") {
      const $step2 = $('[data-step="2"][data-package="basic"]');
      const $step2Button = $step2.find(".add-to-cart");
      const $step3Button = $(
        '[data-step="3"][data-package="basic"] .complete-step'
      );

      expandStep(2, "basic");

      const $increase = $step2.find("#increase");
      const $decrease = $step2.find("#decrease");
      const $ticketAmt = $step2.find(".tickets-amount");
      const $f1price = $step2.find(".f1-package-price");
      const initialPrice = parseFloat($f1price.text().replace("£", ""));

      function updateTicketPrice($ticketAmt, $f1price, initialPrice) {
        const ticketCount = parseInt($ticketAmt.text());
        $f1price.text(`£${initialPrice * ticketCount}`);
      }

      $increase.off("click").on("click", function () {
        const ticketCount = parseInt($ticketAmt.text()) + 1;
        $ticketAmt.text(ticketCount);
        updateTicketPrice($ticketAmt, $f1price, initialPrice);
      });

      $decrease.off("click").on("click", function () {
        let ticketCount = parseInt($ticketAmt.text());
        if (ticketCount > 1) {
          ticketCount--;
          $ticketAmt.text(ticketCount);
          updateTicketPrice($ticketAmt, $f1price, initialPrice);
        }
      });

      $step2Button.off("click").on("click", function () {
        //const ticketsAmount = parseInt($ticketAmt.text());
        basicpackagePrice = parseFloat($f1price.text().replace("£", ""));
        console.log(basicpackagePrice);

        // const data = {
        //   action: "add_ticket_to_cart",
        //   tickets_amount: ticketsAmount,
        //   package_price: packagePrice,
        //   security: electraVars.ajax_nonce_add_to_cart,
        // };

        $step2Button.text("Added to Cart");
        expandStep(3, "basic");

        // $.ajax({
        //   type: "POST",
        //   url: electraVars.ajax_url,
        //   data: data,
        //   success: function (response) {
        //     if (response.success) {
        //       $step2Button.text("Added to Cart");
        //       expandStep(3, "basic");
        //     } else {
        //       alert(
        //         "There was an error adding the item to the cart. Please try again."
        //       );
        //     }
        //   },
        //   error: function () {
        //     alert("There was an error with the request. Please try again.");
        //   },
        // });
      });

      // $step3Button.off("click").on("click", function () {
      //   expandStep(4, "basic");
      // });
    } else if (selectedPackage === "vip") {
      expandStep(2, "vip");

      const $step2 = $('[data-step="2"][data-package="vip"]');
      const $step2Button = $step2.find(".add-to-cart");

      const $increase = $step2.find("#increase");
      const $decrease = $step2.find("#decrease");
      const $ticketAmt = $step2.find(".tickets-amount");
      const $f1price = $step2.find(".f1-package-price");
      const basePrice = parseFloat($f1price.text().replace("£", "")); // Get the base price

      let ticketCount = parseInt($ticketAmt.text());

      function updateVipPrice() {
        $f1price.text(`£${ticketCount * basePrice}`);
      }

      // Increase button functionality
      $increase.off("click").on("click", function () {
        ticketCount++;
        $ticketAmt.text(ticketCount);
        updateVipPrice(); // Update price after increasing
      });

      // Decrease button functionality
      $decrease.off("click").on("click", function () {
        if (ticketCount > 1) {
          ticketCount--;
          $ticketAmt.text(ticketCount);
          updateVipPrice(); // Update price after increasing
        }
      });

      // When Add to Cart is clicked, scroll to the contact form
      $step2Button.off("click").on("click", function () {
        $step2Button.text("Added to Cart");
        $("html, body").animate(
          {
            scrollTop: $(".quote-container").offset().top,
          },
          200
        );
      });

      // Form submission for VIP request
      $("#quoteRequestForm").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        const ticketsAmount = ticketCount; // Use the captured ticket count
        const packagePrice = (ticketsAmount * basePrice).toFixed(2); // Calculate total price

        // Serialize form data
        const formData = $(this).serialize();

        // Add extra data to formData
        const extendedFormData =
          formData +
          `&tickets_amount=${ticketsAmount}&package_price=${packagePrice}`;

        const data = {
          action: "handle_vip_contact_form",
          form_data: extendedFormData,
          security: electraVars.ajax_nonce_quote_contact,
        };

        console.log(extendedFormData);
        $.ajax({
          type: "POST",
          url: electraVars.ajax_url,
          data: data,
          success: function (response) {
            console.log("AJAX Response:", response);
            if (response.success) {
              alert("Your request has been sent successfully!");
              $("#quoteRequestForm")[0].reset(); // Reset form
              $("html, body").animate({ scrollTop: 0 }, 200); // Optional: scroll to top
            } else {
              alert(
                "There was an error sending your request. Please try again."
              );
            }
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error); // Log detailed error
            alert(
              "There was an error with the request. Please check the console for details."
            );
          },
        });
      });
    }
  });

  // $('.payment-btn').on('click', function (e) {
  //     e.preventDefault();

  //     const data = {
  //         action: "generate_checkout_id",
  //         security: electraVars.ajax_nonce_checkout_id,
  //     };

  //     $.ajax({
  //         url: electraVars.ajax_url,
  //         type: 'POST',
  //         data: data,
  //         success: function (response) {
  //             try {
  //                 let parsedResponse = JSON.parse(response);
  //                 console.log('Parsed AJAX Response:', parsedResponse);

  //                 if (parsedResponse.success) {
  //                     var checkoutId = parsedResponse.checkoutId;
  //                     console.log('Checkout ID:', checkoutId);
  //                     loadPaymentWidget(checkoutId);
  //                 } else {
  //                     console.log('Error generating checkout ID:', parsedResponse.message);
  //                     alert('Error generating checkout ID. Please try again.');
  //                 }
  //             } catch (error) {
  //                 console.error('Response Parsing Error:', error, response);
  //                 alert('Error processing the response. Please try again.');
  //             }
  //         },
  //         error: function (xhr, status, error) {
  //             console.error('AJAX Error:', status, error);
  //             alert('Error communicating with the server. Please try again.');
  //         }
  //     });
  // });

  // function loadPaymentWidget(checkoutId) {
  //   $('.paymentWidgets').attr('data-checkoutid', checkoutId);

  //   const scriptUrl = `https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId=${checkoutId}`;
  //   console.log('Loading Payment Widget from:', scriptUrl);

  //   // Remove any existing script with the same URL
  //   $('script[src^="https://eu-test.oppwa.com/v1/paymentWidgets.js"]').remove();

  //   // Create a new script element
  //   var script = document.createElement('script');
  //   script.src = scriptUrl;
  //   script.async = true;
  //   script.onerror = function () {
  //       console.error('Failed to load payment widget script');
  //       alert('Failed to load payment widget. Please try again.');
  //   };

  //   document.body.appendChild(script);

  //   window.wpwlOptions = {
  //       shopperResultUrl: window.location.href, // Hash prevents hard redirect
  //       onReady: function () {
  //           console.log('Payment widget is ready');
  //       },
  //       onError: function (error) {
  //           console.error('Payment error:', error);
  //       },
  //       onResult: function (result) {
  //           console.log('Payment result:', result);

  //           // Handle the transaction result without redirecting
  //           if (result.result && result.result.code === "000.000.000") {
  //               alert('Payment Successful!');
  //           } else {
  //               alert('Payment Failed: ' + (result.result ? result.result.description : 'Unknown error'));
  //           }

  //           return false; // Prevent default redirect behavior
  //       }
  //   };
  // }

  // Auto-formatting for card number and expiry date fields using jQuery
  $("#card-number").on("input", function () {
    let cardNumber = $(this).val().replace(/\D/g, ""); // Remove all non-numeric characters
    if (cardNumber.length > 16) cardNumber = cardNumber.slice(0, 16);

    // Add a space every 4 digits
    $(this).val(cardNumber.replace(/(\d{4})(?=\d)/g, "$1 "));
  });

  $("#expiry-date").on("input", function () {
    let expiryDate = $(this).val().replace(/\D/g, ""); // Remove all non-numeric characters

    // Limit to 4 digits (MMYY format)
    if (expiryDate.length > 4) expiryDate = expiryDate.slice(0, 4);

    // Add a slash after the month (MM/YY format)
    if (expiryDate.length > 2) {
      expiryDate = expiryDate.slice(0, 2) + "/" + expiryDate.slice(2);
    }
    $(this).val(expiryDate);
  });

  $(".payment-btn").click(function (event) {
    event.preventDefault(); // Prevent default form submission
    // Get form data
    let firstName = $("#first-name").val();
    let lastName = $("#last-name").val();
    let email = $("#payment-email").val().trim().toLowerCase();
    let confirmEmail = $("#confirm-email").val().trim().toLowerCase();
    let phoneNumber = $("#phone-number").val();
    let country = $("#country").val();
    let nationality = $("#nationality").val();
    let cardNumber = $("#card-number").val().replace(/\D/g, "");
    let expiryDate = $("#expiry-date").val().split("/");
    let expiryMonth = expiryDate[0];
    let expiryYear = "20" + expiryDate[1];
    let cvv = $("#cvv").val();
    let amount = basicpackagePrice;
    const policyAccepted = $("#policy-check").is(":checked");
    const termsAccepted = $("#terms-check").is(":checked");

    //console.log(basicpackagePrice)

    if (
      !firstName ||
      !lastName ||
      !email ||
      !confirmEmail ||
      !phoneNumber ||
      !country ||
      !nationality ||
      !cardNumber ||
      !expiryMonth ||
      !expiryYear ||
      !cvv
    ) {
      alert("Please fill out all required fields.");
      return;
    }

    if (!policyAccepted) {
      alert("Please adhere to the Privacy Policy");
      return;
    }
    // Validate terms checkbox
    if (!termsAccepted) {
      alert("Please accept the Terms & Conditions.");
      return;
    }

    if (email !== confirmEmail) {
      console.log("Email:", email);
      console.log("Confirm Email:", confirmEmail);
      alert("Emails do not match.");
      return;
    }

    // Send AJAX request
    $.ajax({
      url: electraVars.ajax_url, // WordPress AJAX URL
      type: "POST",
      data: {
        action: "handle_checkout",
        first_name: firstName,
        last_name: lastName,
        email: email,
        phone_number: phoneNumber,
        country: country,
        nationality: nationality,
        card_number: cardNumber,
        expiry_month: expiryMonth,
        expiry_year: expiryYear,
        cvv: cvv,
        amount: amount,
      },
      success: function (response) {
        console.log("AJAX Success Response:", response); // Debugging
        if (response.success) {
          alert("Payment successful! Order created.");
          expandStep(4, "basic");
        } else {
          alert("Payment failed: " + response.message);
        }
      },
      error: function (error) {
        console.log("AJAX Error Response:", error); // Debugging
        alert("An error occurred. Please try again.");
      },
    });
  });
});
