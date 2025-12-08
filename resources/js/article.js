import SocialShareButtons from 'social-share-buttons';
import 'social-share-buttons/styles';

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('share-buttons');
    if (container) {
        new SocialShareButtons({
            container: '#share-buttons',
            platforms: ['x', 'facebook', 'linkedin'],
            labels: {
                x: 'Post on X',
                facebook: 'Share on Facebook',
                linkedin: 'Share on LinkedIn'
            }
        });
    }
});
