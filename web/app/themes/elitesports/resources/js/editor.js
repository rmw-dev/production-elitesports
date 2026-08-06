import domReady from '@wordpress/dom-ready';
import { dispatch, select, subscribe } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';

const AUTOSAVE_NOTICE_IDS = new Set([
  'autosave-exists',
  'wpEditorAutosaveRestore',
]);

domReady(() => {
  subscribe(() => {
    const notices = select(noticesStore).getNotices() || [];

    notices.forEach((notice) => {
      if (!notice?.id || !AUTOSAVE_NOTICE_IDS.has(notice.id)) {
        return;
      }

      dispatch(noticesStore).removeNotice(notice.id, notice.context || 'global');
    });
  });
});
