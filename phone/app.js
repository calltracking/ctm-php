/* 
  Example CallTrackingMetrics Phone Embed
*/

async function refreshToken() {
  const res = await fetch("/ctm-phone-access", { method: "POST", headers: { "Content-Type": "application/json" }});
  const data = await res.json();

  // on load we'll set the token...
  phone.accessToken = data;
}

async function main() {
  const phone = document.querySelector("ctm-phone-embed");
  console.log("CallTrackingMetrics Phone Embed Example", phone);

  // fires an event 30/60 seconds before the token expires
  phone.addEventListener("ctm:tokenExpiringSoon", refreshToken);

  // fires an event when the token is required
  phone.addEventListener("ctm:requiresToken", refreshToken);

  // fires an event when the token is assigned via accessToken= 
  phone.addEventListener("ctm:accessTokenAssigned", async (e) => {
    console.log("did we assign the token");
  });

  phone.addEventListener('ctm:ready', (e) => {
    console.log('ctm:ready', e);
    phone.style.visibility = 'visible';
    // device element when not popout is created automatically one once ready we can interact with it
    // end user has to click into the frame to enable audio for incoming ringing and call audio
    const device = document.querySelector('ctm-device-embed');
    if (device) {
      device.scrollIntoView({ behavior: 'smooth' });
    }
  });

  // each time the agent status changes the status event will fire.
  phone.addEventListener('ctm:status', (e) => {
    const status = e.detail.status;
    document.getElementById('status').innerHTML = status;
  });

  // when a phone call or activity is in progress this event will trigger
  phone.addEventListener('ctm:live-activity', (e) => {
    const call = e.detail.activity;
    document.querySelectorAll(".live-call").forEach((el) => {
      el.classList.add("active");
    });
  });

  // while a call is in progress this event will trigger
  phone.addEventListener('ctm:live-activity-progress', (e) => {
    const activity = e.detail.activity;
    const progressText = e.detail.progressText;
    document.querySelector(".time-on-call").innerHTML = progressText;
  });

  // when a phone call or activity ends this event will trigger
  phone.addEventListener('ctm:end-activity', (e) => {
    const call = e.detail.activity;
    document.querySelectorAll(".live-call").forEach((el) => {
      el.classList.remove("active");
    });
  });

  // tell the device in to make a phone call when an element is clicked with a phone number - be sure the number is formatted with +E.164
  // by default the user's tracking number will be used as the caller id
  document.querySelectorAll('.call-button').forEach((el) => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      const phoneNumber = e.currentTarget.getAttribute('href').replace('tel:', '');
      phone.call(phoneNumber);
    });
  });

  // on enter keypress from the input field
  document.querySelector('.start-call-number').addEventListener('enterValidNumber', (e) => {
    const phoneNumber = e.detail.value;
    phone.call(phoneNumber);
  });

  document.querySelector(".start-call").addEventListener('click', (e) => {
    e.preventDefault();
    const phoneNumber = e.currentTarget.closest(".row").querySelector("ctm-phone-input").value;
    phone.call(phoneNumber);
  });

  document.querySelector('.hold-button').addEventListener('click', (e) => {
    phone.hold();
  });

  document.querySelector('.mute-button').addEventListener('click', (e) => {
    phone.mute();
  });

  document.querySelector('.end-call-button').addEventListener('click', (e) => {
    phone.hangup();
  });
  document.querySelector('.transfer-to-number-button').addEventListener('click', (e) => {
    const dial = e.currentTarget.closest('.input-group').querySelector('ctm-phone-input').value;
    phone.transfer({ object: 'receiving_number', dial });
  });

  document.querySelector('.add-number-button').addEventListener('click', (e) => {
    const dial = e.currentTarget.closest('.input-group').querySelector('ctm-phone-input').value;
    phone.add({ object: 'receiving_number', dial });
  });
}

document.addEventListener('DOMContentLoaded', main);
