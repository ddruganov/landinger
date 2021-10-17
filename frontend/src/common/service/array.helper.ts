export default class ArrayHelper {
    public static listToTree<T>(data: T[]) {

    }

    public static treeToList(data: any[], depth = 0): any[] {
        const input = JSON.parse(JSON.stringify(data)) as any[];
        const output: any[] = [];

        input.forEach(row => {
            row.depth = depth;
            const children = (row.children || []) as any[];
            row.hasChildren = children.length > 0;
            delete row.children;

            output.push(row, ...ArrayHelper.treeToList(children, depth + 1));
        });

        return output;
    }
}