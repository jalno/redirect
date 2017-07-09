import Add from "./Address/Add";
import Edit from "./Address/Edit";
export default class Address{
	public static initIfNeeded(){
		Add.initIfNeeded();
		Edit.initIfNeeded();
	}
}