import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('form-content');
    const toc = document.getElementById('toc');
    
    if (content && toc) {
        const headings = content.querySelectorAll('h1');
        
        // Generate table of contents
        headings.forEach((heading, index) => {
            const link = document.createElement('a');
            link.textContent = heading.textContent;
            link.href = `#heading-${index}`;
            link.className = 'block text-blue-600 hover:text-blue-800';
            toc.appendChild(link);

            heading.id = `heading-${index}`;
        });

        const tocLinks = toc.querySelectorAll('a');

        // Smooth scrolling for ToC links
        tocLinks.forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Highlight current section in ToC
        function highlightTocLink() {
            let scrollPosition = window.scrollY;

            headings.forEach((heading, index) => {
                if (heading.offsetTop <= scrollPosition + 100) {
                    tocLinks.forEach((link) => link.classList.remove('font-bold'));
                    tocLinks[index].classList.add('font-bold');
                }
            });
        }

        window.addEventListener('scroll', highlightTocLink);
    }
});