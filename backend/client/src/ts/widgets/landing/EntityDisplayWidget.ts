type EntityDisplayWidgetConfig = {
    selector: string;
};

class EntityDisplayWidget {
    private $el: HTMLDivElement;
    private group: HTMLDivElement;
    private childrenContainer: HTMLDivElement;

    constructor(config: EntityDisplayWidgetConfig) {
        this.$el = document.querySelector(config.selector);
        this.group = this.$el.querySelector('.group') as HTMLDivElement;
        this.childrenContainer = this.$el.querySelector('.children') as HTMLDivElement;

        this.init();
    }

    private init() {
        const toggleArrow = this.group.querySelector('.arrow') as HTMLDivElement;
        this.group.addEventListener('click', () => {
            const childrenAreHidden = this.childrenContainer.hasAttribute('hidden')
            childrenAreHidden ?
                this.childrenContainer.removeAttribute('hidden') :
                this.childrenContainer.setAttribute('hidden', '');
            this.animateChildren(childrenAreHidden);

            const classMap = {
                'false': 'down',
                'true': 'left'
            };
            toggleArrow.classList.remove(classMap[String(childrenAreHidden)]);
            toggleArrow.classList.add(classMap[String(!childrenAreHidden)]);
        });
    }

    private animateChildren(animateOpening: boolean) {

        const finalHeight = this.childrenContainer.getBoundingClientRect().height;
        const startHeight = animateOpening ? 0 : finalHeight;
        const endHeight = animateOpening ? finalHeight : 0;

        this.childrenContainer.style.height = `${startHeight}px`;
        setTimeout(() => {
            this.childrenContainer.style.height = `${endHeight}px`;
        }, 1);
        setTimeout(() => {
            endHeight ?
                this.childrenContainer.removeAttribute('height') :
                this.childrenContainer.style.removeProperty('height');
        }, 250);
    }
}