/* ====================================================================
   SnowPuri — contact.js
   AJAX submission to /api/contact.php
   ==================================================================== */
(function () {
  'use strict';

  const I18N = {
    ko: {
      success: '전송되었습니다. 빠르게 답장드릴게요.',
      error:   '전송에 실패했어요. 잠시 후 다시 시도하거나 직접 snow@snowpuri.com 으로 보내주세요.',
      sending: '전송 중...',
      err_required: '이 필드는 필수입니다.',
      err_email: '유효한 이메일을 입력해주세요.',
      err_min: '10자 이상 입력해주세요.',
    },
    en: {
      success: 'Sent! We\'ll get back to you soon.',
      error:   'Something went wrong. Please try again, or email snow@snowpuri.com directly.',
      sending: 'Sending...',
      err_required: 'This field is required.',
      err_email: 'Please enter a valid email.',
      err_min: 'Please enter at least 10 characters.',
    },
  };

  function getLang() {
    return document.body.dataset.lang === 'en' ? 'en' : 'ko';
  }
  function tr(key) { return (I18N[getLang()] || I18N.en)[key]; }

  function init() {
    const form = document.querySelector('[data-component="contact"]');
    if (!form) return;

    const status = form.querySelector('[data-form-status]');
    const submit = form.querySelector('[data-submit]');
    const fields = {
      name:    form.querySelector('[data-field="name"]'),
      email:   form.querySelector('[data-field="email"]'),
      message: form.querySelector('[data-field="message"]'),
    };

    function setStatus(kind, msg) {
      status.className = 'form-status is-visible form-status--' + kind;
      status.textContent = msg;
    }
    function clearStatus() {
      status.className = 'form-status';
      status.textContent = '';
    }
    function setFieldError(name, msg) {
      const f = fields[name];
      if (!f) return;
      f.classList.add('is-error');
      const err = f.querySelector('[data-err]');
      if (err) err.textContent = msg;
    }
    function clearFieldErrors() {
      Object.values(fields).forEach((f) => {
        if (!f) return;
        f.classList.remove('is-error');
        const err = f.querySelector('[data-err]');
        if (err) err.textContent = '';
      });
    }

    function validate() {
      clearFieldErrors();
      let ok = true;
      const data = {
        name:    fields.name.querySelector('input').value.trim(),
        email:   fields.email.querySelector('input').value.trim(),
        message: fields.message.querySelector('textarea').value.trim(),
      };

      if (!data.name || data.name.length < 2) {
        setFieldError('name', tr('err_required'));
        ok = false;
      }
      if (!data.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
        setFieldError('email', tr('err_email'));
        ok = false;
      }
      if (!data.message || data.message.length < 10) {
        setFieldError('message', tr('err_min'));
        ok = false;
      }
      return ok ? data : null;
    }

    async function onSubmit(e) {
      e.preventDefault();
      clearStatus();

      const data = validate();
      if (!data) return;

      const formData = new FormData(form);
      // Honeypot — 이미 formData에 포함됨 (input name="website")

      submit.disabled = true;
      const orig = submit.textContent;
      submit.textContent = tr('sending');

      try {
        const res = await fetch('/api/contact.php', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'fetch' },
        });
        const json = await res.json().catch(() => ({ ok: false, error: tr('error') }));

        if (res.ok && json.ok) {
          setStatus('ok', json.message || tr('success'));
          form.reset();
        } else {
          setStatus('err', json.error || tr('error'));
          if (Array.isArray(json.fields)) {
            json.fields.forEach((f) => setFieldError(f, tr('err_required')));
          }
        }
      } catch (err) {
        setStatus('err', tr('error'));
      } finally {
        submit.disabled = false;
        submit.textContent = orig;
      }
    }

    form.addEventListener('submit', onSubmit);

    // 입력 시 에러 클리어
    Object.values(fields).forEach((f) => {
      if (!f) return;
      const input = f.querySelector('input, textarea');
      if (!input) return;
      input.addEventListener('input', () => {
        f.classList.remove('is-error');
        const err = f.querySelector('[data-err]');
        if (err) err.textContent = '';
      });
    });
  }

  document.addEventListener('DOMContentLoaded', init);
})();
