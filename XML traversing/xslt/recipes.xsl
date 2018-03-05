<xsl:stylesheet version="2.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <style>
                    body {  background-color: black;
                    color:white;}
                </style>
                <title>
                    Chef's Recipes
                </title>
            </head>
            <body>
                <p align = "center" style="font-size:36px;"><xsl:value-of select="collection/description"/></p>
                <xsl:for-each select="collection/recipe">
                    <br/><br/><br/> <h2><p align = "center">Recipe Name: <xsl:value-of select="title"/></p></h2><hr/><br/>
                    <h3><p style="color:blue;">Recipe id: <xsl:value-of select="@id"/></p></h3>
                    
                    <h3><p style="color:blue;"><b>Date:</b> <xsl:value-of select="date"/></p><br/></h3>
                    <br/><br/> <p style="color:blue;"> <b>Ingredients List:</b> <br/></p>
                    <xsl:for-each select="ingredient">
                        <p style="color:blue;"><b>Name:</b> <xsl:value-of select="@name"/></p>
                        <p style="color:blue;"><b> Unit:</b> <xsl:value-of select="@unit"/></p>
                        <p style="color:blue;"><b> Amount:</b> <xsl:value-of select="@amount"/><br/></p>
                        
                        <br/> <xsl:for-each select="ingredient">
                            <p style="color:blue;"><b>Name:</b> <xsl:value-of select="@name"/></p>
                            <p style="color:blue;"><b> Unit:</b> <xsl:value-of select="@unit"/></p>
                            <p style="color:blue;"><b> Amount:</b> <xsl:value-of select="@amount"/><br/></p>
                            <br/>  <xsl:for-each select="ingredient">
                                <p style="color:blue;"><b>Name:</b> <xsl:value-of select="@name"/></p>
                                <p style="color:blue;"><b> Unit:</b> <xsl:value-of select="@unit"/></p>
                                <p style="color:blue;"><b> Amount:</b> <xsl:value-of select="@amount"/><br/></p>
                                <br/> <xsl:for-each select="ingredient">
                                    <p style="color:blue;"><b>Name:</b> <xsl:value-of select="@name"/></p>
                                    <p style="color:blue;"><b> Unit:</b> <xsl:value-of select="@unit"/></p>
                                    <p style="color:blue;"><b> Amount:</b> <xsl:value-of select="@amount"/><br/></p>
                                </xsl:for-each><br/>
                                <xsl:if test="preparation/node()">
                                    <h3><p style="color:red;"> Steps to cook  <xsl:value-of select="@name"/>:</p></h3><br/>
                                </xsl:if>
                                <xsl:copy-of select="preparation/node()">
                                </xsl:copy-of> <br/>
                            </xsl:for-each><br/>
                            <xsl:if test="preparation/node()">
                                <h3> <p style="color:red;"> Steps to cook  <xsl:value-of select="@name"/>:</p></h3><br/>
                            </xsl:if>
                            <xsl:copy-of select="preparation/node()">
                            </xsl:copy-of> <br/>
                        </xsl:for-each><br/>
                        <xsl:if test="preparation/node()">
                            <h3> <p style="color:red;"> Steps to cook  <xsl:value-of select="@name"/>:</p></h3><br/>
                        </xsl:if>
                        <xsl:copy-of select="preparation/node()">
                        </xsl:copy-of> <br/>
                    </xsl:for-each><br/>
                    <p style="color:red;font-size:36px;">Steps to cook <xsl:value-of select="title"/>:</p>
                    <xsl:copy-of select="preparation/node()">
                    </xsl:copy-of><br/><br/>
                    <p style="color:blue;"><b>Comments:</b></p> <xsl:value-of select="comment"/><br/><br/>
                    <p style="color:blue;"><b>Nutrition Values:</b></p>
                    <xsl:for-each select="nutrition/@*">
                        <xsl:value-of select="concat(name(), ': ', ., ',')"/>
                    </xsl:for-each><hr/>
                    
                </xsl:for-each>
                
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
