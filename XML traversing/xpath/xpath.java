package xpath;


import java.util.Scanner;

import javax.xml.xpath.*;
import org.xml.sax.InputSource;
import org.w3c.dom.*;

public class xpath 
{

	 static void print ( Node e ) {
			if (e instanceof Text)
			    System.out.print(((Text) e).getData());
			else {
			    NodeList c = e.getChildNodes();
			    System.out.print("<"+e.getNodeName());
			    NamedNodeMap attributes = e.getAttributes();
			    for (int i = 0; i < attributes.getLength(); i++)
				System.out.print(" "+attributes.item(i).getNodeName()
						 +"=\""+attributes.item(i).getNodeValue()+"\"");
			    System.out.print(">");
			    for (int k = 0; k < c.getLength(); k++)
				print(c.item(k));
			    System.out.print("</"+e.getNodeName()+">");
			}
		    }

		    static void eval ( String query, String document ) throws Exception {
			XPathFactory xpathFactory = XPathFactory.newInstance();
			XPath xpath = xpathFactory.newXPath();
			InputSource inputSource = new InputSource(document);
			NodeList result = (NodeList) xpath.evaluate(query,inputSource,XPathConstants.NODESET);
			System.out.println("XPath query: "+query);
			for (int i = 0; i < result.getLength(); i++)
			    print(result.item(i));
			System.out.println();
		    }

		    public static void main ( String[] args ) throws Exception
		    {
                
                Scanner key = new Scanner(System.in);
                int i=6;
                int option;
        System.out.println("The Following Xpath Queries can be performed:\n");
        System.out.println("1.Print the titles of all articles whose one of the authors is David Maier.");
        System.out.println("2.Print the titles of all articles whose first author is David Maier.");
        System.out.println("3.Print the titles of all articles whose authors include David Maier and Stanley B. Zdonik.");
        System.out.println("4.Print the titles of all articles in volume 19/number 2.");
        System.out.println("5.Print the titles and the init/end pages of all articles in volume 19/number 2 whose authors include Jim Gray.");
        System.out.println("6.Print the volume and number of all articles whose authors include David Maier. (note: we need the number entry of an article, not the number of articles).");
        System.out.println("7. EXIT\n\n");    
        for(i=0;i<6;i++)
        {
                System.out.println("\nEnter the option number:");
                option = key.nextInt();
             if(option == 1)
             {//1st Query:
			  eval("/SigmodRecord/issue/articles/article/authors/author[text()='David Maier']/../parent::article/title","SigmodRecord.xml");
             }
            else if(option == 2)
            {//2nd Query
		    eval("/SigmodRecord/issue/articles/article/authors/author[@position='00' and text()='David Maier' ]/../parent::article/title","SigmodRecord.xml");
            }
            else if(option == 3)
            {
		    //3rd Query
		      eval("//issue/articles/article[authors/author/text()='David Maier' and authors/author/text()='Stanley B. Zdonik']/title","SigmodRecord.xml");
            }
            else if(option == 4)
            {
		   //4th Query
		     eval("//issue[volume='19' and number='2']/articles/article/title","SigmodRecord.xml");
            }
            else if(option == 5)
            {
		   //5th Query
		     eval("/SigmodRecord/issue[volume='19' and number='2']/articles/article/authors/author[text()='Jim Gray']/../parent::article/*[self::title or self::initPage or self::endPage ]","SigmodRecord.xml");
            }
          else if(option == 6)
          {
		    //6th Query
		    eval("/SigmodRecord/issue/articles/article/authors/author[text()='David Maier']/../../../../*[self::volume or self::number ]","SigmodRecord.xml");
		    	
          }
          else
          {
        	  System.exit(1);
          }
       }
    }
	
		 
		    
}
