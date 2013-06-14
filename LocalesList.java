import java.text.SimpleDateFormat;
import java.util.*;

public class LocalesList {

   static public void main(String[] args) {

      Locale list[] = SimpleDateFormat.getAvailableLocales();
      Set set = new TreeSet();
      for (int i = 0; i < list.length; i++) {
         set.add(list[i].getDisplayName() 
               +"\t\t\t:\t"+ list[i].toString());
      }
      Iterator it = set.iterator();
      while (it.hasNext()) {
         System.out.println(it.next() );
      }
   }
}
