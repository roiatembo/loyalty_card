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
      $('.form-posting').css('display', 'block');

    },
    error: function (error) {
      $(".loader").hide();
      console.log(error);
    },
  });
});
