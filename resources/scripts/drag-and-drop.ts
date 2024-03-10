function dragAndDrop(element: Element, destination: Element) {
    type FakeDragDataTransfer = Pick<DataTransfer, 'setData' | 'getData'> & {
        data: Record<string, any>;
    };

    class FakeDragEvent extends CustomEvent<FakeDragDataTransfer> {
        dataTransfer?: FakeDragDataTransfer;
    }

    function createFakeDragEvent(typeOfEvent: string): FakeDragEvent {
        const event = new FakeDragEvent(typeOfEvent);

        event.dataTransfer = {
            data: {},
            setData: function (key: string, value: any) {
                this.data[key] = value;
            },
            getData: function (key: string) {
                return this.data[key];
            }
        };

        return event;
    }

    const dragStartEvent = createFakeDragEvent('dragstart');
    const dropEvent = createFakeDragEvent('drop');
    const dragEndEvent = createFakeDragEvent('dragend');

    element.dispatchEvent(dragStartEvent);

    dropEvent.dataTransfer = dragStartEvent.dataTransfer;

    destination.dispatchEvent(dropEvent);

    dragEndEvent.dataTransfer = dropEvent.dataTransfer;

    element.dispatchEvent(dragEndEvent);
}
