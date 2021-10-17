export default class ArrayHelper {
    public static listToTree(dataset: any[]) {
        /** thanks so much: https://stackoverflow.com/a/40732240/5320740 */
        const hashTable = Object.create(null);
        dataset.forEach(aData => hashTable[aData.id] = { ...aData, children: [] });
        const dataTree: any[] = [];
        dataset.forEach(aData => {
            if (aData.parentId) {
                hashTable[aData.parentId].children.push(hashTable[aData.id]);
            }
            else {
                dataTree.push(hashTable[aData.id]);
            }
        });

        return ArrayHelper.deepSort(dataTree, (left, right) => left.weight - right.weight);
    }

    public static deepSort(array: any[], predicate: (a: any, b: any) => number) {
        array.sort(predicate);
        array.forEach(item => {
            item.children = ArrayHelper.deepSort(item.children, predicate);
        });

        return array;
    }

    public static treeToList(data: any[]): any[] {
        const input = JSON.parse(JSON.stringify(data)) as any[];
        const output: any[] = [];

        input.forEach(row => {
            const children = (row.children || []) as any[];
            delete row.children;

            output.push(row, ...ArrayHelper.treeToList(children));
        });

        return output;
    }

    public static trace(haystack: any[], needle: any): any[] {
        if (!haystack.length) {
            return [];
        }

        const trace = [];
        for (const item of haystack) {
            if (item.id === needle.id) {
                trace.push(item);
                break;
            }
            if (!item.children) {
                continue;
            }

            const childrenTrace = ArrayHelper.trace(item.children, needle);
            if (childrenTrace.length) {
                trace.push(item, ...childrenTrace);
            }
        }

        return trace;
    }

}