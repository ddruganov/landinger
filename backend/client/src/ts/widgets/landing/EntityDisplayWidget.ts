type EntityDisplayWidgetConfig = {
    selector: string;
};

class EntityDisplayWidget {
    private $el: HTMLDivElement;

    constructor(config: EntityDisplayWidgetConfig) {
        this.$el = document.querySelector(config.selector);

        this.init();
    }

    private init() {
        const button = this.$el.querySelector('.child-toggler');
        button?.addEventListener('click', () => {
            const childrenContainer = this.$el.querySelector('.children') as HTMLDivElement;
            const areHidden = childrenContainer.style.display === 'none';
            childrenContainer.style.display = areHidden ? 'flex' : 'none';
            button.textContent = areHidden ? '+' : '-';
        });
    }
}