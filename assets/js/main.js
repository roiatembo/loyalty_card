$(".loader").hide();

$("#loyaltyForm").submit(function (event) {
  var formData = $(this).serialize();

  $("html, body").animate(
    {
      scrollTop: $(".loader-section").offset().top,
    },
    1000
  );

  $(".loader").show();

  $.ajax({
    url: "printCard.php",
    type: "POST",
    data: formData,
    success: function (response) {
      $("#loyaltyForm")[0].reset();
      $(".loader").hide();
      $(".form-posting").html(
        `<span>Congratulations your card is ready, <a href='${response}'>click here</a> to download</span>`
      );
      $(".form-posting").css("display", "block");
      console.log(response);
    },
    error: function (error) {
      $(".loader").hide();
      console.log(error);
    },
  });
});

function isValidEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

$('input[name="email"]').on("keydown", function (event) {
  $("#email").addClass("input-error");
  var emailValue = $(this).val();
  var valid = isValidEmail(emailValue);
  if (valid == true) {
    $(this).removeClass("input-error");
    $(this).addClass("input-valid")
  }
});

$("#phoneNumber").on("keyup", function (event) {
  var phoneValue = $(this).val()
  
  if (phoneValue.length == 4) {
    if (phoneValue[3] != " ") {
      $(this).val(phoneValue.substring(0, 3)+ " " + phoneValue[3])
    }
  }

  if (phoneValue.length == 8) {
    if (phoneValue[7] != " ") {
      $(this).val(phoneValue.substring(0, 7)+ " " + phoneValue[7])
    }
  }
})